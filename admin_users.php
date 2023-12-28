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

if(isset($_GET['user_type'])){
    $user_type = $_GET['user_type'];
}else{
    $user_type = '';
}



if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id= ?");
    $delete_users->execute(array($delete_id));
    setcookie('link', 1, time() + 60 * 30, '/');
    header('Location:./admin_users.php');
    exit();  
}

if(isset($_COOKIE['link'])){
    $user_type = $_COOKIE['user_type'];
    setcookie('link', '', time() - 1, '/');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/admin_style.css">
</head>
<body>

<!-- header section -->
<?php require('./admin_header.php'); ?>

<!-- user-accounts section -->
<section class="user-accounts">

    <h1 class="title">user accounts</h1>

    <div class="box-container">
        <?php 
            if($user_type == 'all'){
                $select_users = $conn->prepare("SELECT * FROM `users`");
                $select_users->execute();               
            }else{

                $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
                $select_users->execute(array($user_type));
            }
            setcookie('user_type', $user_type, time() + 30* 60, '/');
            if($select_users->rowCount() > 0){
                while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
        ?>
         
            <div class="box" style="<?php if($fetch_users['id'] == $admin_id){echo 'display:none';} ?>">
                <img src="./assets/uploaded_img/<?= $fetch_users['image']; ?>" alt="">
                <p>user id :  <span><?= $fetch_users['id']; ?></span></p>
                <p>username :  <span><?= $fetch_users['name']; ?></span></p>
                <p>email :  <span><?= $fetch_users['email']; ?></span></p>
                <p>user type :  <span style="color:<?php if($fetch_users['user_type'] == 'admin'){echo  'var(--orange)';} ?>">
                <?= $fetch_users['user_type']; ?></span></p>
                <a href="./admin_users.php?delete=<?= $fetch_users['id'];?>"
                onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
            </div>

        <?php 
        }
        }
        ?>
    </div>

</section>


<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

