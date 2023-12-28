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

if(isset($_POST['send'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    $select_message = $conn->prepare("SELECT * FROM `message` WHERE name= ? AND email= ? AND number= ?
    AND message= ? ");
    $select_message->execute(array($name, $email, $number, $msg));
    if($select_message->rowCount() > 0){
        $message[]= 'already sent message!';
    }else{
        $insert_message = $conn->prepare("INSERT INTO `message` (user_id, name, email, number, message) VALUES
        (?, ?, ?, ?, ?)");
        $insert_message->execute(array($user_id, $name, $email, $number, $msg));
        $message[] = 'sent message successfully!';
    }
    $_SESSION['message']= $message;
    header('Location:./contact.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- contact section -->
<section class="contact">

    <h1 class="title">get in touch</h1>

    <form action="" method="post">
        <input type="text" name="name" class="box" placeholder="enter your name" required>
        <input type="email" name="email" class="box" placeholder="enter your email" required>
        <input type="number" name="number" class="box" min="0" placeholder="enter your number" required>
        <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10"></textarea>
        <input type="submit" name="send" class="btn" value="send message">
    </form>

</section>







<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

