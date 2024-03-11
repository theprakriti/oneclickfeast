<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_aboutus'])) {

    $about = $_POST['about'];
    $aboutus_sideimage = $_FILES['aboutus_sideimage']['name'];
    $tmp_name = $_FILES['aboutus_sideimage']['tmp_name'];
   
    $aboutus_sideimage = time().'_'.$aboutus_sideimage;

    $path = "../images/".$aboutus_sideimage;
    move_uploaded_file($tmp_name, $path);

    $aboutus = $conn->prepare("SELECT * FROM `aboutus` WHERE is_admin = 1");
    $aboutus->execute();
    $aboutus = $aboutus->fetch(PDO::FETCH_ASSOC);

    if($aboutus) {
        $delete_aboutus_sideimage = $aboutus['side_image'];
        unlink("../images/".$delete_aboutus_sideimage);
        $update_aboutus = $conn->prepare("UPDATE `aboutus` SET about = ?, side_image = ?, is_admin = ? WHERE is_admin = 1");


    } else {
        $update_aboutus = $conn->prepare("INSERT INTO `aboutus` (about, side_image, is_admin) VALUES (?, ?, ?)");
    }

    $update_aboutus->execute([$about, $aboutus_sideimage, true]);
    $message[] = 'About Us updated!'; // Changed to a single message variable
    
}

$aboutus = $conn->prepare("SELECT * FROM `aboutus` WHERE is_admin = 1");
$aboutus->execute();
$aboutus = $aboutus->fetch(PDO::FETCH_ASSOC);

if($aboutus) {
    $about = $aboutus['about'];
    $aboutus_sideimage = $aboutus['side_image'];
} else {
    $about = '';
    $aboutus_sideimage = '';
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed reviews section starts  -->

<section class="placed-reviews">

   <h1 class="heading">About Us</h1>

   <div class="box-container"  >
   <form action="" method="post" enctype="multipart/form-data">
   <div class="add-products" style="display: flex;gap: 20px;align-items: center;">


  <div>
       <h2>Side Image</h2>
         <img src="../images/./<?=$aboutus_sideimage ?>" style="width: 100%;height: 100%;border: 1px solid black; margin: 5px;"  alt="About Us">
        
         <div>
         <h2 for="" style="margin: 5px;">Upload a Image</h2>
         <input type="file" name="aboutus_sideimage" id="aboutus_sideimage" class="form-control" required>
         </div>
   </div>

   <div>
  <h2 style="margin: 5px;">About us Content</h2>
   <textarea name="about" aria-required="Please write something" id="about" cols="30" rows="10" class="form-control" placeholder="About Us" required><?=$about ?></textarea>
    <button type="submit" name="update_aboutus" class="btn" style="margin-top: 10px;">Update</button>
   </div>




</div>
</form>
   </div>

</section>

<!-- placed reviews section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>