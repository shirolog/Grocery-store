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



if(isset($_POST['add_to_cart'])){

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $p_name = $_POST['p_name'];
    $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
    $p_price = $_POST['p_price'];
    $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
    $p_img = $_POST['p_img'];
    $p_img = filter_var($p_img, FILTER_SANITIZE_STRING);
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);


    $select_cart = $conn->prepare("SELECT * FROM  `cart` WHERE user_id= ? AND name= ?");
    $select_cart->execute(array($user_id, $p_name));

    if($select_cart->rowCount() > 0){
        $message[] = 'already added to cart!';
    }else{

        $select_wishlist = $conn->prepare("SELECT * FROM  `wishlist` WHERE user_id= ? AND name= ?");
        $select_wishlist->execute(array($user_id, $p_name));
        if($select_wishlist->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id= ? AND name= ?");
            $delete_wishlist->execute(array($user_id, $p_name));
        }

        $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES
        (?, ?, ?, ?, ?, ?)");
        $insert_cart->execute(array($user_id, $pid, $p_name, $p_price, $p_qty, $p_img));
        $message[] = 'added to cart!';
    }
    $_SESSION['message'] = $message;
    header('Location:./wishlist.php');
    exit();
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE id= ?");
    $delete_wishlist->execute(array($delete_id));
    header('Location:./wishlist.php');
    exit();
}

if(isset($_GET['delete_all'])){
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id= ?");
    $delete_wishlist->execute(array($user_id));
    header('Location:./wishlist.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wishlist</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- wishlist section -->
<section class="wishlist">

    <h1 class="title">products added</h1>

    <div class="box-container">
        <?php 
            $grand_total = 0;
            $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id= ?");
            $select_wishlist->execute(array($user_id));
            if($select_wishlist->rowCount() > 0){
                while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
                $grand_total += $fetch_wishlist['price'];
        ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
                <input type="hidden" name="p_name" value="<?= $fetch_wishlist['name']; ?>">
                <input type="hidden" name="p_price" value="<?= $fetch_wishlist['price']; ?>">
                <input type="hidden" name="p_img" value="<?= $fetch_wishlist['image']; ?>">
                <a href="./wishlist.php?delete=<?= $fetch_wishlist['id']; ?>" class="fas fa-times"
                onclick="return confirm('delete this from wishlist?');"></a>
                <a href="./view_page.php?pid=<?= $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
                <img src="./assets/uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="">
                <div class="name"><?= $fetch_wishlist['name']; ?></div>
                <div class="price">$<?= number_format($fetch_wishlist['price']); ?>/-</div>
                <input type="number" name="p_qty" class="qty" min="1" value="1">
                <input type="submit" name="add_to_cart" class="btn" value="add to cart">
            </form>
        <?php 
        }
        }else{
            echo '<p class="empty">your wishlist is empty</p>';
        }
        ?>
    </div>

        <div class="wishlist-total">
            <p>grand total : <span>$<?= number_format($grand_total); ?>/-</span></p>
            <a href="./shop.php" class="option-btn">continue shopping</a>
            <a href="./wishlist.php?delete_all" class="delete-btn <?php echo ($grand_total >= 1)? '' : 'disabled'; ?>"
            onclick="return confirm('delete all from wishlist?');">delete all</a>
        </div>
</section>







<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

