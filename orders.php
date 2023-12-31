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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orders</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- placed-orders section -->
<section class="placed-orders">

    <h1 class="title">placed orders</h1>

    <div class="box-container">
        <?php 
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id= ?");
            $select_orders->execute(array($user_id));
            if($select_orders->rowCount() > 0){
                while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
        ?>

            <div class="box">
                <p>placed on : <span><?=  $fetch_orders['placed_on']; ?></span></p>
                <p>name : <span><?=  $fetch_orders['name']; ?></span></p>
                <p>number : <span><?=  $fetch_orders['number']; ?></span></p>
                <p>email : <span><?=  $fetch_orders['email']; ?></span></p>
                <p>address : <span><?=  $fetch_orders['address']; ?></span></p>
                <p>payment method : <span><?=  $fetch_orders['method']; ?></span></p>
                <p>your orders : <span><?=  $fetch_orders['total_products']; ?></span></p>
                <p>total price : <span>$<?=  $fetch_orders['total_price']; ?>/-</span></p>
                <p>payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){echo 'red';}else{echo 'green';} ?>;"><?=  $fetch_orders['payment_status']; ?></span></p>
            </div>

        <?php 
        }
        }else{
        echo '<p class="empty">no orders placed yet!</p>';
        }
        ?>
    </div>

</section>







<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

