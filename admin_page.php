<?php 
require('./connect.php');
session_start();

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
}else{
    $admin_id = '';
    header('Location:./login.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/admin_style.css">
</head>
<body>

<!-- header section -->
<?php require('./admin_header.php'); ?>

<!-- dashboard section -->
<section class="dashboard">
    
    <h1 class="title">dashboard</h1>

    <div class="box-container">

        <?php
            $total_pendings = 0; 
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status= ?");
            $select_orders->execute(array('pending'));
            if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){

                $total_pendings += $fetch_orders['total_price'];
            }    
        ?>
            <div class="box">
                <h3>$<?= number_format($total_pendings); ?>/-</h3>
                <p>total pendings</p>
                <a href="./admin_orders.php?status=pending" class="btn">see orders</a>
            </div>
        <?php 
        }else{
        ?>

            <div class="box">
                <h3>$<?= number_format(0); ?>/-</h3>
                <p>total pendings</p>
                <a href="./admin_orders.php?status=pending" class="btn">see orders</a>
            </div>

        <?php 
        }
        ?>

        <?php
            $total_completed = 0; 
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status= ?");
            $select_orders->execute(array('completed'));
            if($select_orders-> rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){

                $total_completed += $fetch_orders['total_price'];
            }
        ?>
            <div class="box">
                <h3>$<?= number_format($total_completed); ?>/-</h3>
                <p>completed orders</p>
                <a href="./admin_orders.php?status=completed" class="btn">see orders</a>
            </div>
        <?php 
        }else{
        ?>
            <div class="box">
                <h3>$<?= number_format(0); ?>/-</h3>
                <p>completed orders</p>
                <a href="./admin_orders.php?status=completed" class="btn">see orders</a>
            </div>

        <?php 
        }
        ?>

        <div class="box">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount();
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>orders placed</p>
            <a href="./admin_orders.php?status=all" class="btn">see orders</a>
        </div>

        <div class="box">
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount();
            ?>
            <h3><?= $number_of_products; ?></h3>
            <p>products added</p>
            <a href="./admin_products.php" class="btn">see products</a>
        </div>

        <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type= ?");
            $select_users->execute(array('user'));
            if($select_users->rowCount() > 0){
            $number_of_users = $select_users->rowCount();
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>total users</p>
            <a href="./admin_users.php?user_type=user" class="btn">see accounts</a>

            <?php 
            }else{
            ?>
                <h3>0</h3>
                <p>total users</p>
                <a href="./admin_users.php?user_type=user" class="btn">see accounts</a>
            <?php 
            }
            ?>
        </div>

    

        <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type= ?");
            $select_users->execute(array('admin'));
            if($select_users->rowCount() > 0){
            $number_of_admins = $select_users->rowCount();
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>total admins</p>
            <a href="./admin_users.php?user_type=admin" class="btn">see accounts</a>

            <?php 
            }else{
            ?>
                <h3>0</h3>
                <p>total users</p>
                <a href="./admin_users.php?user_type=admin" class="btn">see accounts</a>
            <?php 
            }
            ?>
        </div>

        <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            if($select_users->rowCount() > 0){
            $number_of_users = $select_users->rowCount();
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>total accounts</p>
            <a href="./admin_users.php?user_type=all" class="btn">see accounts</a>

            <?php 
            }else{
            ?>
                <h3>0</h3>
                <p>total users</p>
                <a href="./admin_users.php?user_type=all" class="btn">see accounts</a>
            <?php 
            }
            ?>
        </div>

        <div class="box">
            <?php
            $select_message = $conn->prepare("SELECT * FROM `message`");
            $select_message->execute();
            $number_of_message = $select_message->rowCount();
            ?>
            <h3><?= $number_of_message; ?></h3>
            <p>total messages</p>
            <a href="./admin_contacts.php" class="btn">see messages</a>
        </div>


    </div>

</section>


<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

