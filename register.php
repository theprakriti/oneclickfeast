<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

$name=$email=$number=$pass=$cpass='';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email or number already exists!';
   }elseif(strlen($name) < 3){
      $message[] = 'name must be at least 3 characters!';
   }elseif(strlen($email) < 8){
      $message[] = 'email must be at least 8 characters!';
   }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $message[] = 'email must be a valid email address!';
   }elseif(!preg_match("/^[0-9]{10}$/", $number)){
      $message[] = 'number must be a valid 10-digit phone number!';
   }elseif(strlen($number) < 10){
      $message[] = 'number must be at least 10 characters!';
   }elseif(strlen($pass) < 8){
      $message[] = 'password must be at least 8 characters!';
   }elseif(strlen($cpass) < 8){
      $message[] = 'confirm password must be at least 8 characters!';
   } else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
         $insert_user->execute([$name, $email, $number, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];

            $_SESSION['registered_success']='You are now registered!';
            header('location:home.php');
         }
      }
   }

}

if($_POST){
$pass=$_POST['pass'];
$cpass=$_POST['cpass'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post" >
      <h3>register now</h3>
      <input type="text" name="name" required pattern="[a-zA-Z ]+" title="Please enter only letters and spaces" placeholder="enter your name" class="box" maxlength="50" value="<?=$name?>">
  <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" value="<?=$email?>">
  <input type="tel" name="number" required pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number" placeholder="enter your number" class="box" maxlength="10" value="<?=$number?>">
  <input type="password" name="pass" required pattern=".{8,}" title="Password must be at least 8 characters" placeholder="enter your password" class="box" maxlength="50" value="<?=$pass?>">
  <input type="password" name="cpass" required pattern=".{8,}" title="Password must be at least 8 characters" placeholder="confirm your password" class="box" maxlength="50" value="<?=$cpass?>">
  <input type="submit" value="register now" name="submit" class="btn">



      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>











<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>



</html>