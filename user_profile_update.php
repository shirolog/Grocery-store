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


if(isset($_POST['update_profile'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    if(!empty($name)){
        $select_users = $conn->prepare("SELECT * FROM `users` WHERE id= ? AND name= ?");
        $select_users->execute(array($user_id, $name));
        if($select_users->rowCount() > 0){
        }else{
            $update_users = $conn->prepare("UPDATE `users` SET  name= ? WHERE id= ?");
            $update_users->execute(array($name, $user_id));
            $message[] = 'name updated successfully!';
        }
    }

    if(!empty($email)){
        $select_users = $conn->prepare("SELECT * FROM `users` WHERE id= ? AND email= ?");
        $select_users->execute(array($user_id, $email));
        if($select_users->rowCount() > 0){
        }else{
            $update_users = $conn->prepare("UPDATE `users` SET  email= ? WHERE id= ?");
            $update_users->execute(array($email, $user_id));
            $message[] = 'email updated successfully!';
        }
    }

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = './assets/uploaded_img/'.$image;
    $old_image = $_POST['old_image'];
    $old_image = filter_var($old_image, FILTER_SANITIZE_STRING);

    if(!empty($image)){
        if($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            $update_users = $conn->prepare("UPDATE `users` SET  image= ? WHERE id= ?");
            $update_users->execute(array($image, $user_id));
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('./assets/uploaded_img/'. $old_image);
            $message[] = 'image updated successfully!';
        }
    }
    
    $empty_pass = 'd41d8cd98f00b204e9800998ecf8427e';
    $old_pass = $_POST['old_pass'];
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $prev_pass = md5($_POST['prev_pass']);
    $prev_pass = filter_var($prev_pass, FILTER_SANITIZE_STRING);
    $new_pass = md5($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $cpass = md5($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        if($old_pass != $empty_pass){
            if($old_pass != $prev_pass){
                $message[] = 'old password not matched!';
            }elseif($new_pass != $cpass){
                $message[] = 'confirm password not matched!';
            }else{
                if($new_pass != $empty_pass){
                    $update_users = $conn->prepare("UPDATE `users` SET  password= ? WHERE id= ?");
                    $update_users->execute(array($cpass, $user_id));
                    $message[] = 'password updated successfully!';
                }else{
                    $message[] = 'please enter new password!';
                }
            }
        }
 
    $_SESSION['message'] = $message;
    header('Location:./user_profile_update.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update user profile</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- update-profile section -->
<section class="update-profile">

    <h1 class="title">update profile</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="old_pass" value="<?= $fetch_users['password']; ?>">
        <input type="hidden" name="old_image" value="<?= $fetch_users['image']; ?>">
       <img src="./assets/uploaded_img/<?= $fetch_users['image']; ?>" alt="">
        <div class="flex">
            <div class="inputBox">
                <span>username : </span>
                <input type="text" name="name" class="box" placeholder="update username"
                value="<?= $fetch_users['name']; ?>">
                <span>email : </span>
                <input type="email" name="email" class="box" placeholder="update email"
                value="<?= $fetch_users['email']; ?>">
                <span>update pic : </span>
                <input type="file" name="image" class="box" accept="image/png, image/jpeg, image/jpg">
            </div>   

            <div class="inputBox">
                <span>old password : </span>
                <input type="password" name="prev_pass" class="box" placeholder="enter previous password">
                <span>new password : </span>
                <input type="password" name="new_pass" class="box" placeholder="enter new password">
                <span>confirm password : </span>
                <input type="password" name="cpass" class="box" placeholder="confirm your password">
            </div>   
        </div>
        <div class="flex-btn">
            <input type="submit" name="update_profile" class="btn" value="update profile">
            <a href="./home.php" class="option-btn">go back</a>
        </div>
            
    </form>

</section>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

