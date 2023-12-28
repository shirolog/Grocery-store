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



if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $delete_message = $conn->prepare("DELETE FROM `message` WHERE id= ?");
    $delete_message->execute(array($delete_id));
    header('Location:./admin_contacts.php');
    exit();  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>messages</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/admin_style.css">
</head>
<body>

<!-- header section -->
<?php require('./admin_header.php'); ?>

<!-- messages section -->
<section class="messages">

    <h1 class="title">messages</h1>

    <div class="box-container">
        <?php 
        $select_message = $conn->prepare("SELECT * FROM `message`");
        $select_message->execute();
        if($select_message->rowCount() > 0){
            while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
        ?>
            <div class="box">
                <p>user id : <span><?= $fetch_message['user_id']; ?></span></p>
                <p>name : <span><?= $fetch_message['name']; ?></span></p>
                <p>number : <span><?= $fetch_message['number']; ?></span></p>
                <p>email : <span><?= $fetch_message['email']; ?></span></p>
                <p>message : <span><?= $fetch_message['message']; ?></span></p>
                <a href="./admin_contacts.php?delete=<?= $fetch_message['id']; ?>"
                onclick="return confirm('delete this message?');" class="delete-btn">delete</a>
            </div>
        <?php 
        }
        }else{
            echo '<p class="empty">you have no message</p>';
        }
        ?>
    </div>

</section>



<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

<img src="" alt="">