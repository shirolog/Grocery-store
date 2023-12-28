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


if(isset($_POST['add_product'])){
 
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = './assets/uploaded_img/'.$image;

    $select_products = $conn->prepare("SELECT * FROM  `products` WHERE name= ?");
    $select_products->execute(array($name));

    if($select_products->rowCount() > 0){
        $message[] = 'product name already exist!';
    }else{
      
        if($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            $insert_products = $conn->prepare("INSERT INTO  `products` (name, category, details, price, image) VALUES
            (?, ?, ?, ?, ?)");
            $insert_products->execute(array($name, $category, $details, $price, $image));
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new product added!';
        }
    }
        $_SESSION['message'] = $message;
        header('Location:./admin_products.php');
        exit();
}

if(isset($_GET['delete'])){

    $delete_id = $_GET['delete'];

    $select_products = $conn->prepare("SELECT * FROM  `products` WHERE id= ?");
    $select_products->execute(array($delete_id));
    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
    unlink('./assets/uploaded_img/'. $fetch_products['image']);

    $delete_products = $conn->prepare("DELETE FROM `products` WHERE id= ?");
    $delete_products->execute(array($delete_id));

    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid= ?");
    $delete_wishlist->execute(array($delete_id));

    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid= ?");
    $delete_cart->execute(array($delete_id));

    header('Location:./admin_products.php');
    exit();
}else{
    $delete_id = '';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/admin_style.css">
</head>
<body>

<!-- header section -->
<?php require('./admin_header.php'); ?>

<!-- add-products section -->
<section class="add-products">

    <h1 class="title">add new product</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <input type="text" name="name" class="box" required
                placeholder="enter product name">
                <select name="category" class="box" required>
                    <option value="" selected disabled>select catrgory</option>
                    <option value="vegitables">vegitables</option>
                    <option value="fruits">fruits</option>
                    <option value="meat">meat</option>
                    <option value="fish">fish</option>
                </select>
            </div>

            <div class="inputBox">
                <input type="number" name="price" class="box" required
                placeholder="enter product price">
                <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png" required>
            </div>
        </div>
        <textarea name="details" class="box" placeholder="enter product details" cols="30" rows="10"></textarea>
        <input type="submit" name="add_product" class="btn" value="add product">
    </form>

</section>

<!-- show-products section -->
<section class="show-products">

    <h1 class="title">products added</h1>

    <div class="box-container">
        <?php 
        $select_products = $conn->prepare("SELECT * FROM  `products`");
        $select_products->execute();
        if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>

          <div class="box">
            <div class="price">$<?= number_format($fetch_products['price']); ?>/-</div>
            <img src="./assets/uploaded_img/<?= $fetch_products['image']; ?>" alt="">
            <div class="name"><?= $fetch_products['name']; ?></div>
            <div class="category"><?= $fetch_products['category']; ?></div>
            <div class="details"><?= $fetch_products['details']; ?></div>
            <div class="flex-btn">
                <a href="./admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                <a href="./admin_products.php?delete=<?= $fetch_products['id']; ?>"
                onclick="return confirm('delete this product?');" class="delete-btn">delete</a>
            </div>
          </div>  

        <?php 
        }
        }else{
            echo '<p class="empty">now products added yet! </p>';
        }
        ?>
    </div>

</section>



<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

