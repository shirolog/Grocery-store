<?php 
require('./connect.php');
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('Location:./login.php');
    exit();
}


if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE id= ?");
    $delete_cart->execute(array($delete_id));
    header('Location:./cart.php');
    exit();
}

if(isset($_GET['delete_all'])){
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id= ?");
    $delete_cart->execute(array($user_id));
    header('Location:./cart.php');
    exit();
}

if(isset($_POST['update_qty'])){

    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);

    $select_cart = $conn->prepare("SELECT * FROM  `cart` WHERE quantity= ? AND pid= ?");
    $select_cart->execute(array($p_qty, $pid));
    if($select_cart->rowCount() > 0){
    }else{
        $update_cart = $conn->prepare("UPDATE  `cart` SET quantity= ? WHERE pid= ?");
        $update_cart->execute(array($p_qty, $pid));
        $message[] = 'cart quantity updated!';
        $_SESSION['message'] = $message;
        header('Location:./cart.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping cart</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- shopping-cart section -->
<section class="shopping-cart">

    <h1 class="title">products added</h1>

    <div class="box-container">
        <?php 
            $grand_total = 0;
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ?");
            $select_cart->execute(array($user_id));
            if($select_cart->rowCount() > 0){
                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total += $sub_total;
        ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_cart['pid']; ?>">
                <a href="./cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times"
                onclick="return confirm('delete this from cart?');"></a>
                <a href="./view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                <img src="./assets/uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                <div class="name"><?= $fetch_cart['name']; ?></div>
                <div class="price">$<?= number_format($fetch_cart['price']); ?>/-</div>
                <div class="flex-btn">
                    <input type="number" name="p_qty" class="qty" min="1" value="<?= $fetch_cart['quantity'] ?>">
                    <input type="submit" name="update_qty" class="option-btn" value="update">
                </div>
                    <div class="sub-total">sub total : <span>$<?= number_format($sub_total); ?>/-</span></div>
            </form>
        <?php 
        }
        }else{
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
    </div>

        <div class="cart-total">
            <p>grand total : <span>$<?= number_format($grand_total); ?>/-</span></p>
            <a href="./shop.php" class="option-btn">continue shopping</a>
            <a href="./cart.php?delete_all" class="delete-btn <?php echo ($grand_total >= 1)? '' : 'disabled'; ?>"
            onclick="return confirm('delete all from cart?');">delete all</a>
            <a href="./checkout.php" class="btn <?php echo ($grand_total >= 1)? '' : 'disabled'; ?>">proceed to checkout</a>
        </div>
</section>








<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

