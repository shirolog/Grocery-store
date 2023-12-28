<?php 
require('./connect.php');
session_start();

if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);



    $select_users = $conn->prepare("SELECT * FROM `users` WHERE  email= ? AND password= ?");
    $select_users->execute(array($email, $pass));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
    
    if($select_users->rowCount() > 0){
        if($fetch_users['user_type'] == 'admin'){
            $_SESSION['admin_id'] = $fetch_users['id'];
            header('Location:admin_page.php');
            exit();
        }elseif($fetch_users['user_type'] == 'user'){
            $_SESSION['user_id'] = $fetch_users['id'];
            header('Location:./home.php');
            exit();
        }else{
            $message[] = 'no user found!';
        }
    }else{
        $message[] = 'incorrect email or password!';
    }
    $_SESSION['message'] = $message;
    header('Location:./login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<?php 
    if(isset($_SESSION['message'])){
        foreach($_SESSION['message'] as $message){
            echo '<div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        }
        unset($_SESSION['message']);
    }
?>

<!-- form-container section -->
<section class="form-container">

    <form action="" method="post" enctype="multipart/form-data">
        <h3>login now</h3>
        <input type="email" name="email" class="box" placeholder="enter your email" required>
        <input type="password" name="pass" class="box" placeholder="enter your password" required>
        <input type="submit" name="submit" class="btn" value="login now">
        <p>don't have an account? <a href="./register.php">register</a></p>
    </form>

</section>
    
</body>
</html>