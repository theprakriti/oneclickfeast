<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_review'])){

   $review_id = $_POST['review_id'];
   $status = $_POST['status'];
   $update_status = $conn->prepare("UPDATE `review` SET status = ? WHERE id = ?");
   $update_status->execute([$status, $review_id]);
   $message[] = 'review status updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_review = $conn->prepare("DELETE FROM `review` WHERE id = ?");
   $delete_review->execute([$delete_id]);
   header('location:reviews.php');
   die;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reviews</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed reviews section starts  -->

<section class="placed-reviews">

   <h1 class="heading">Reviews</h1>

   <div class="box-container" style="display: flex;flex-wrap: wrap; gap: 30px;">

   <?php
      $select_reviews = $conn->prepare("SELECT review.*,users.name FROM review JOIN users ON review.user_id=users.id;");
      $select_reviews->execute();
      if($select_reviews->rowCount() > 0){
         while($fetch_reviews = $select_reviews->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" style="border: 1px solid black;padding: 10px;">
      <p> user name : <span><?= $fetch_reviews['name']; ?></span> </p>
      <p> review : <span><?= $fetch_reviews['review']; ?></span> </p>
      <p> stars : <span><?= $fetch_reviews['stars']; ?></span> </p>
      <p> status : <span><?= $fetch_reviews['status']; ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="review_id" value="<?= $fetch_reviews['id']; ?>">
         <select name="status" class="drop-down">
            <option value="" selected disabled><?= $fetch_reviews['status']; ?></option>
            <option value="pending">pending</option>
            <option value="approved">approved</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="update" class="option-btn" name="update_review">
            <a href="reviews.php?delete=<?= $fetch_reviews['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </div>
      </form>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no reviews yet!</p>';
   }
   ?>

   </div>

</section>

<!-- placed reviews section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>