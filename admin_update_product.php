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

if(isset($_GET['update'])){
    
    $update_id = $_GET['update'];


}else{
    $update_id = '';
}

if(isset($_POST['update_product'])){

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    if(!empty($name)){
        $select_products = $conn->prepare("SELECT * FROM  `products`  WHERE name= ? AND id= ?");
        $select_products->execute(array($name, $pid));
        if($select_products->rowCount() > 0){
        }else{
            $update_products = $conn->prepare("UPDATE  `products` SET name= ? WHERE id= ?");
            $update_products->execute(array($name, $pid));
            $message[] = 'name updated successfully!';
        }
    }

    if(!empty($price)){
        $select_products = $conn->prepare("SELECT * FROM  `products`  WHERE price= ? AND id= ?");
        $select_products->execute(array($price, $pid));
        if($select_products->rowCount() > 0){
        }else{
            $update_products = $conn->prepare("UPDATE  `products` SET price= ? WHERE id= ?");
            $update_products->execute(array($price, $pid));
            $message[] = 'price updated successfully!';
        }
    }

    if(!empty($category)){
        $select_products = $conn->prepare("SELECT * FROM  `products`  WHERE category= ? AND id= ?");
        $select_products->execute(array($category, $pid));
        if($select_products->rowCount() > 0){
        }else{
            $update_products = $conn->prepare("UPDATE  `products` SET category= ? WHERE id= ?");
            $update_products->execute(array($category, $pid));
            $message[] = 'category updated successfully!';
        }
    }

    if(!empty($details)){
        $select_products = $conn->prepare("SELECT * FROM  `products`  WHERE details= ? AND id= ?");
        $select_products->execute(array($details, $pid));
        if($select_products->rowCount() > 0){
        }else{
            $update_products = $conn->prepare("UPDATE  `products` SET details= ? WHERE id= ?");
            $update_products->execute(array($details, $pid));
            $message[] = 'details updated successfully!';
        }
    }

    
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = './assets/uploaded_img/'.$image;
    $old_img = $_POST['old_img'];
    $old_img = filter_var($old_img, FILTER_SANITIZE_STRING);

    if(!empty($image)){
        if($image_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            $select_products = $conn->prepare("SELECT * FROM  `products`  WHERE image= ? AND id= ?");
            $select_products->execute(array($image, $pid));
            if($select_products->rowCount() > 0){
            }else{
                $update_products = $conn->prepare("UPDATE  `products` SET image= ? WHERE id= ?");
                $update_products->execute(array($image, $pid));
                move_uploaded_file($image_tmp_name, $image_folder);
                unlink('./assets/uploaded_img/'. $old_img);
                $message[] = 'image updated successfully!';
            }
    
        }
    }
    $_SESSION['message'] = $message;
    header('Location:./admin_update_product.php?update='. $pid);
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update products</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/admin_style.css">
</head>
<body>

<!-- header section -->
<?php require('./admin_header.php'); ?>

<!-- update-product section -->
<section class="update-product">

    <h1 class="title">update product</h1>

    <?php
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id= ?");
        $select_products->execute(array($update_id));
        if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
    ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_img" value="<?= $fetch_products['image']; ?>">
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <img src="./assets/uploaded_img/<?= $fetch_products['image']; ?>" alt="">
            <input type="text" name="name" class="box" placeholder="enter product name"
            value="<?= $fetch_products['name']; ?>">
            <input type="number" name="price" class="box" placeholder="enter product price"
            value="<?= number_format($fetch_products['price']); ?>" min="0">
            <select name="category" class="box" required>
                <option selected><?= $fetch_products['category']; ?></option>
                <option value="vegitables">vegitables</option>
                <option value="fruits">fruits</option>
                <option value="meat">meat</option>
                <option value="fish">fish</option>
            </select>
            <textarea name="details" class="box" cols="30" rows="10" 
            placeholder="enter product details"><?= $fetch_products['details']; ?></textarea>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
            <div class="flex-btn">
                <input type="submit" name="update_product" class="btn" value="update product">
                <a href="./admin_products.php" class="option-btn">go back</a>
            </div>
        </form>

    <?php 
    }
    }else{
        echo '<p class="empty">no products found!</p>';
    }
    ?>
</section>


<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>

