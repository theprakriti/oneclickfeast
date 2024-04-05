<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'username already exists!';
   }elseif(strlen($name) < 3){
      $message[] = 'name must be at least 3 characters!';
   }elseif(strlen($pass) < 8){
      $message[] = 'password must be at least 8 characters!';
   }elseif(strlen($cpass) < 8){
      $message[] = 'confirm password must be at least 8 characters!';
   } else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
              $insert_admin->execute([$name, $cpass]);
             $message[] = 'new admin registered!';
         }
      }
   }
   // if($select_admin->rowCount() > 0){
   //    $message[] = 'username already exists!';
   // }else{
   //    if($pass != $cpass){
   //       $message[] = 'confirm passowrd not matched!';
   //    }else{
   //       $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
   //       $insert_admin->execute([$name, $cpass]);
   //       $message[] = 'new admin registered!';
   //    }
   // }



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- register admin section starts  -->

<section class="form-container">



   <form action="" method="POST">
      <h3>register new</h3>
      <input type="text" name="name" required pattern="[a-zA-Z ]+" title="Please enter only letters and spaces" placeholder="enter your name" class="box" maxlength="50">
      <input type="password" name="pass" required pattern=".{8,}" title="Password must be at least 8 characters" placeholder="enter your password" class="box" maxlength="50">
       <input type="password" name="cpass" required pattern=".{8,}" title="Password must be at least 8 characters" placeholder="confirm your password" class="box" maxlength="50">
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register admin section ends -->
















<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>