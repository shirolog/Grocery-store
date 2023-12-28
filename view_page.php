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

if(isset($_GET['pid'])){
    $pid = $_GET['pid'];
}else{
    $pid = '';
}


if(isset($_POST['add_to_wishlist'])){

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $p_name = $_POST['p_name'];
    $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
    $p_price = $_POST['p_price'];
    $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
    $p_img = $_POST['p_img'];
    $p_img = filter_var($p_img, FILTER_SANITIZE_STRING);


    $select_wishlist = $conn->prepare("SELECT * FROM  `wishlist` WHERE user_id= ? AND name= ?");
    $select_wishlist->execute(array($user_id, $p_name));

    $select_cart = $conn->prepare("SELECT * FROM  `cart` WHERE user_id= ? AND name= ?");
    $select_cart->execute(array($user_id, $p_name));

    if($select_wishlist->rowCount() > 0){
        $message[] = 'already added to wishlist!';
    }elseif($select_cart->rowCount() > 0){
        $message[] = 'already added to cart!';
    }else{
        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES
        (?, ?, ?, ?, ?)");
        $insert_wishlist->execute(array($user_id, $pid, $p_name, $p_price, $p_img));
        $message[] = 'added to wishlist!';
    }
    $_SESSION['message'] = $message;
    header('Location:./view_page.php?pid='. $pid);
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
    header('Location:./view_page.php?pid='. $pid);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>quick view</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>

<!-- quick-view section -->
<section class="quick-view">

    <h1 class="title">quick view</h1>

    <?php 
        $select_products = $conn->prepare("SELECT * FROM  `products` WHERE id= ?");
        $select_products->execute(array($pid));
        if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="p_img" value="<?= $fetch_products['image']; ?>">
                <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
                <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <div class="price">$<span><?= number_format($fetch_products['price']); ?></span>/-</div>
                <img src="./assets/uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                <div class="name"><?= $fetch_products['name']; ?></div>
                <div class="details"><?= $fetch_products['details']; ?></div>
                <input type="number" name="p_qty" class="qty" min="1" value="1">
                <input type="submit" name="add_to_wishlist" class="option-btn" value="add to wishlist">
                <input type="submit" name="add_to_cart" class="btn" value="add to cart">
            </form>
        <?php 
        }
        }else{
            echo '<p class="empty">no products added yet!</p>';
        }        
    ?>

</section>








<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

