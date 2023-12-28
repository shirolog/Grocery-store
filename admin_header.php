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

<header class="header">

    <section class="flex">
        <a href="admin_page.php" class="logo">Admin <span>Panel</span></a>

        <nav class="navbar">
            <a href="admin_page.php">home</a>
            <a href="admin_products.php">products</a>
            <a href="admin_orders.php?status=all">orders</a>
            <a href="admin_users.php?user_type=all">users</a>
            <a href="admin_contacts.php">contacts</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php 
                $select_users = $conn->prepare("SELECT * FROM `users` WHERE id= ?");
                $select_users->execute(array($admin_id));
                $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
            ?>

            <img src="./assets/uploaded_img/<?= $fetch_users['image']; ?>" alt="">
            <p><?= $fetch_users['name']; ?></p>
            <a href="admin_update_profile.php" class="btn">update profile</a>
            <a href="logout.php" class="delete-btn" onclick="return confirm('logout from this website?');">logout</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
        </div>
    </section>

</header>