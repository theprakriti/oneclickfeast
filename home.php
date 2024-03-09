<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css\style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

 <div class="hero" >

   <div class="swiper hero-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">

            <div class="image">
               <img src="images/backg4.jpg" style="width: 100vw;height: 420px;object-fit: cover;" alt="">
            </div>

            <div style="position: absolute;left: 27%;text-align: center;">
               <h1 class="title">Welcome to OneClick Feast</h1>
               <p >Order your favorite food from your favorite restaurant with just one click!</p>
              
            </div>
        </div>
         
         
      </div>

      <div class="swiper-pagination"></div>

   </div>

</div>  

<section class="category">

   <h1 class="title">Food Category</h1>

   <div class="box-container">

      <a href="category.php?category=Fast Food" class="box">
         <img src="images/cat1.png" alt="">
         <h3>Fast Food</h3>
      </a>

      <a href="category.php?category=Main Dish" class="box">
         <img src="images/cat2.png" alt="">
         <h3>Main Dishes</h3>
      </a>

      <a href="category.php?category=Beverage" class="box">
         <img src="images/cat3.png" alt="">
         <h3>Beverages</h3>
      </a>

      <a href="category.php?category=Dessert" class="box">
         <img src="images/cat4.png" alt="">
         <h3>Desserts</h3>
      </a>

   </div>

</section>




<section class="products">

   <h1 class="title">Popular Dishes</h1>

   <div class="box-container">

      <?php
        $select_products = $conn->prepare("SELECT * FROM products ORDER BY search_count DESC LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""  style="height: 200px;width: 100%;object-fit: cover;">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Rs</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn"  style="font-size: 16px; padding: 10px 20px;">view all</a>
   </div>

</section>

<!--  Review Section -->
<section class="reviews">
    <h1 class="title">Customer's Reviews</h1>
    <div class="swiper reviews-slider">
        <div class="swiper-wrapper">
            <?php
            $select_reviews = $conn->prepare("SELECT review.*, users.name FROM review JOIN users ON review.user_id = users.id WHERE review.status='approved' ORDER BY review.id DESC LIMIT 6");
            $select_reviews->execute();

            


           ?>

         
           <?php
while ($fetch_reviews = $select_reviews->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="swiper-slide slide">
        <div class="box" >
        <p> <span><?= $fetch_reviews['review']; ?></span></p>
            <p>-<span><?= $fetch_reviews['name']; ?></span></p>

            <?php

            if($fetch_reviews['stars'] == 1){
                echo '<p> <span>⭐</span></p>';
            }else if($fetch_reviews['stars'] == 2){
                  echo '<p> <span>⭐⭐</span></p>';
            }else if($fetch_reviews['stars'] == 3){

                  echo '<p> <span>⭐⭐⭐</span></p>';
            }else if($fetch_reviews['stars'] == 4){
                  
                     echo '<p> <span>⭐⭐⭐⭐</span></p>';
            }else if($fetch_reviews['stars'] == 5){
                     
                        echo '<p> <span>⭐⭐⭐⭐⭐</span></p>';
               }
            ?>
          
           

        </div>
    </div>
    <?php

}

?>

          
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <?php

    if(isset($_SESSION['user_id'])){
      ?>
    
    <div class="center-container">
        <a href="addreview.php" id="addreviewbtn">Add Review</a>
    </div>

    <!-- <div style="display: flex; justify-content: center;">
        <a href="addreview.php" style="background-color: yellow; font-size: 16px; padding: 10px 20px; margin: 30px; cursor: pointer;" id="addreviewbtn">Add Review</a>
    </div> -->
    <?php

      }

      ?>
</section>




















<?php include 'components/footer.php'; ?>


<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>


<script>

   <?php
      
      if(isset($_SESSION['login_success'])){
         echo "alert('".$_SESSION['login_success']."')";
      }

      unset($_SESSION['login_success']);

      if(isset($_SESSION['registered_success'])){
         echo "alert('".$_SESSION['registered_success']."')";
      }


      unset($_SESSION['registered_success']);
     
      ?>
</script>

<script>



var swiper = new Swiper(".hero-slider", {
   loop:true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
});



var reviews_swiper = new Swiper(".reviews-slider", {
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