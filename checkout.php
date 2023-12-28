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

if(isset($_POST['order'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);

    $flat = $_POST['flat'];
    $flat = filter_var($flat, FILTER_SANITIZE_STRING);
    $street = $_POST['street'];
    $street = filter_var($street, FILTER_SANITIZE_STRING);
    $city = $_POST['city'];
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $state = $_POST['state'];
    $state = filter_var($state, FILTER_SANITIZE_STRING);
    $country = $_POST['country'];
    $country = filter_var($country, FILTER_SANITIZE_STRING);
    $pin_code = $_POST['pin_code'];
    $pin_code = filter_var($pin_code, FILTER_SANITIZE_STRING);
    $address = 'flat no. '. $flat. ' ' .$street. ' ' .$city. ' ' .$state. ' ' .$country. '-'. $pin_code;
    $placed_on = date('d-m-Y');

    $total_price = 0;
    $total_products = [];

    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ?");
    $select_cart->execute(array($user_id));
    if($select_cart->rowCount() > 0){
        while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
            $total_products[] = $fetch_cart['name']. '('. $fetch_cart['quantity'].')';
            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
            $total_price += $sub_total;
        }
    }

    $total_products = implode(',', $total_products);

    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE name= ? AND number= ? AND
    email= ? AND method= ? AND address= ? AND total_products= ? AND total_price= ?");
    $select_orders->execute(array($name, $number, $email, $method, $address, 
    $total_products, $total_price));
    
    if($total_price == 0){
        $message[] = 'your cart is empty!"';
    }elseif($select_orders->rowCount() > 0){
        $message[] = 'order placed already!';
    }else{
        $insert_orders = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address,
        total_products, total_price, placed_on) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_orders->execute(array($user_id, $name, $number, $email, $method, $address, $total_products, $total_price,
        $placed_on));
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id= ?");
        $delete_cart->execute(array($user_id));
        $message[] = 'order placed successfully!';
    }
    $_SESSION['message'] = $message;
    header('Location:./checkout.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- display-orders section -->

<section class="display-orders">

    <?php 
        $grand_total = 0;
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ?");
        $select_cart->execute(array($user_id));
        if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $sub_total;
    ?>
        <p><?= $fetch_cart['name']; ?> <span>(<?= '$'.number_format($fetch_cart['price']). '/-'. 'X'  .$fetch_cart['quantity']; ?></span>)</p>
    <?php 
    }
    }else{
        echo '<p class="empty">your cart is empty</p>';
    }
    ?>
    <div class="grand-total">grand total : <span>$<?= number_format($grand_total); ?>/-</span></div>
</section>

<!-- checkout-orders section -->
<section class="checkout-orders">


    <form action="" method="post">
    <h3>place your order</h3>
        <div class="flex">
            <div class="inputBox">
                <span>your name :</span>
                <input type="text" name="name" class="box" placeholder="enter your name" required>
            </div>

            <div class="inputBox">
                <span>your number :</span>
                <input type="number" name="number" class="box" placeholder="enter your number" required>
            </div>

            <div class="inputBox">
                <span>your email :</span>
                <input type="email" name="email" class="box" placeholder="enter your email" required>
            </div>

            <div class="inputBox">
                <span>payment method :</span>
                <select name="method" class="box">
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="credit card">credit card</option>
                    <option value="paytm">paytm</option>
                    <option value="paypal">paypal</option>
                </select>
            </div>

            <div class="inputBox">
                <span>address line 01 :</span>
                <input type="text" name="flat" class="box" placeholder="e.g. flat number" required>
            </div>

            <div class="inputBox">
                <span>address line 02 :</span>
                <input type="text" name="street" class="box" placeholder="e.g. street name" required>
            </div>

            <div class="inputBox">
                <span>city :</span>
                <input type="text" name="city" class="box" placeholder="e.g. shibuya" required>
            </div>

            <div class="inputBox">
                <span>state :</span>
                <input type="text" name="state" class="box" placeholder="e.g. tokyo" required>
            </div>

            <div class="inputBox">
                <span>country :</span>
                <input type="text" name="country" class="box" placeholder="e.g. japan" required>
            </div>

            <div class="inputBox">
                <span>pin code :</span>
                <input type="number" name="pin_code" class="box" min="0" placeholder="e.g. 123456" required>
            </div>

        </div>

        <input type="submit" name="order" class="btn <?php echo ($grand_total > 1)? '' : 'disabled'; ?>" value="place order">

    </form>

</section>







<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

