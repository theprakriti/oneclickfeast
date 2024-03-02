<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['btnreview'])){


    if(empty($_POST['review'])){
        $message[] = 'Review is required!';
    }

    if(empty($_POST['star'])){
        $message[] = 'Rank is required!';
    }

    if($_POST['star'] < 0 || $_POST['star'] > 5){
        $message[] = 'Rank must be between 0 and 5!';
    }else{

        $review = $_POST['review'];
   $review = filter_var($review, FILTER_SANITIZE_STRING);
   $star = $_POST['star'];
    $star = filter_var($star, FILTER_SANITIZE_STRING);

   $select_review = $conn->prepare("SELECT * FROM `review` WHERE review = ? AND user_id = ? AND status = ?  AND stars = ?");
   $select_review->execute([$review, $user_id, "pending", $star]);

   if($select_review->rowCount() > 0){
      $message[] = 'already submitted review!';
   }else{

      $insert_review = $conn->prepare("INSERT INTO `review`(review, user_id, status,stars) VALUES(?,?,?,?)");
      $insert_review->execute([$review, $user_id, "pending", $star]);

      $message[] = 'sent review successfully!';

   }
    }


   

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Review</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Contact Us</h3>
   <p><a href="home.php">Home</a> <span> / Add Review</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="post">
         <h3>Review Us</h3>
         <textarea name="review" class="box" required placeholder="Enter your review" maxlength="500" cols="30" rows="10"></textarea>
       <div>
       
        <input name="star"  min="0" max="5" required class="box" placeholder="Rank us 1 to 5">
       </div>
          
         <input type="submit" value="Submit Review" name="btnreview" class="btn">
      </form>

   </div>

</section>

<!-- contact section ends -->










<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->








<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>

</html>