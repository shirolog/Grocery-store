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
        <a href="home.php" class="logo">Grocery <span>.</span></a>

        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="shop.php">shop</a>
            <a href="orders.php">orders</a>
            <a href="about.php">about</a>
            <a href="contact.php">contact</a>
  
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <a href="./search_page.php" class="fas fa-search"></a>
            <?php 
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ?");
                $select_cart->execute(array($user_id));
                $count_cart = $select_cart->rowCount();

                $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id= ?");
                $select_wishlist->execute(array($user_id));
                $count_wishlist = $select_wishlist->rowCount();
            ?>
            <a href="./wishlist.php"><i class="fas fa-heart"></i><span>(<?= $count_wishlist; ?>)</span></a>
            <a href="./cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart; ?>)</span></a>
        </div>

        <div class="profile">
            <?php 
                $select_users = $conn->prepare("SELECT * FROM `users` WHERE id= ?");
                $select_users->execute(array($user_id));
                $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
            ?>

            <img src="./assets/uploaded_img/<?= $fetch_users['image']; ?>" alt="">
            <p><?= $fetch_users['name']; ?></p>
            <a href="user_profile_update.php" class="btn">update profile</a>
            <a href="logout.php" class="delete-btn" onclick="return confirm('logout from this website?');">logout</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
        </div>
    </section>

</header>