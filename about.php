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
    <title>about</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<!-- header section -->
<?php require('./header.php'); ?>


<!-- about section -->
<section class="about">

    <div class="row">

        <div class="box">
            <img src="./assets/images/about-img-1.png" alt="">
            <h3>why choose us?</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
             Officiis quae doloremque, delectus accusamus molestias, eaque
            asperiores animi quisquam, labore ducimus reprehenderit modi similique corporis nisi. Velit consectetur quisquam provident vero.</p>
            <a href="./contact.php" class="btn">contact us</a>
        </div>

        
        <div class="box">
            <img src="./assets/images/about-img-2.png" alt="">
            <h3>what we provide?</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
             Officiis quae doloremque, delectus accusamus molestias, eaque
            asperiores animi quisquam, labore ducimus reprehenderit modi similique corporis nisi. Velit consectetur quisquam provident vero.</p>
            <a href="./shop.php" class="btn">our shop</a>
        </div>

    </div>

</section>
    
<!-- reviews section -->
<section class="reviews">

    <h1 class="title">clients reviews</h1>

    <div class="box-container">

        <div class="box">
            <img src="./assets/images/pic-1.png" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur omnis sequi facilis incidunt voluptatem veritatis,
             perspiciatis fuga accusantium animi laborum!</p>
             <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
             </div>
             <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="./assets/images/pic-2.png" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur omnis sequi facilis incidunt voluptatem veritatis,
             perspiciatis fuga accusantium animi laborum!</p>
             <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
             </div>
             <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="./assets/images/pic-3.png" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur omnis sequi facilis incidunt voluptatem veritatis,
             perspiciatis fuga accusantium animi laborum!</p>
             <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
             </div>
             <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="./assets/images/pic-4.png" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur omnis sequi facilis incidunt voluptatem veritatis,
             perspiciatis fuga accusantium animi laborum!</p>
             <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
             </div>
             <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="./assets/images/pic-5.png" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur omnis sequi facilis incidunt voluptatem veritatis,
             perspiciatis fuga accusantium animi laborum!</p>
             <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
             </div>
             <h3>john deo</h3>
        </div>

        <div class="box">
            <img src="./assets/images/pic-6.png" alt="">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur omnis sequi facilis incidunt voluptatem veritatis,
             perspiciatis fuga accusantium animi laborum!</p>
             <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
             </div>
             <h3>john deo</h3>
        </div>

    </div>

</section>







<!-- footer section -->
<?php require('./footer.php'); ?>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

