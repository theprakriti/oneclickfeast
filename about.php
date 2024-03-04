<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

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
   <h3>About us</h3>
   <p><a href="home.php">Home</a> <span> / About</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images\backg3.jpg" alt="">
      </div>

      <div class="content">
         <h3>Why choose us?</h3>
         <p>Choose us for effortless online food ordering. Enjoy a seamless experience with our diverse menu, quick delivery, and a commitment to delivering fresh, flavorful meals right to your doorstep.</p>
         <a href="menu.php" class="btn " style="font-size: 16px; padding: 10px 20px;">Our menu</a>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->

<section class="steps">

   <h1 class="title">Simple Steps</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/step1.png" alt="">
         <h3>Choose Order</h3>
         <p>Explore our diverse menu and choose from a tantalizing array of dishes crafted to delight your taste buds.</p>
      </div>

      <div class="box">
         <img src="images/step2.png" alt="">
         <h3>Fast Delivery</h3>
         <p>Experience swift and reliable delivery, ensuring your favorite meals arrive at your doorstep within just 30 minutes.</p>
      </div>

      <div class="box">
         <img src="images/step3.png" alt="">
         <h3>Enjoy Food</h3>
         <p>Dive into deliciousness – your food is on its way to steal the show! <br>Enjoy!</br></p>
      </div>

   </div>

</section>

<!-- steps section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="title">Customer's Reivews</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/pic1.jpg" alt="">
            <p>Effortless ordering, diverse cuisines, and prompt delivery. Seamless experience with real-time tracking. Outstanding!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Samantha</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic2.jpg" alt="">
            <p>Quick and convenient. Good food quality and reliable delivery. More vegetarian options would be a plus.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
              
            </div>
            <h3>Jenny</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic3.jpg" alt="">
            <p>Decent selection, but delivery time inconsistency. Responsive customer service.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>John</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic4.jpg" alt="">
            <p>Smooth ordering, reliable delivery. It's a pleasant surprise to get some great deals on my favorite meals.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
              
            </div>
            <h3>Krishna</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic5.jpg" alt="">
            <p>Intuitive website, fantastic options, and on-time deliveries. Loyalty program is a nice bonus. Highly recommended!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Rose</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic6.jpg" alt="">
            <p>website is user-friendly, deliveries are consistently on time, and the food is top-notch. Can't ask for more!</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Shruthi</h3>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<!-- reviews section ends -->



















<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->=






<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>

</body>


</html>