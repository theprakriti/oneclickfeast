<?php

if(isset($_SESSION['success_message'])){
   $message = $_SESSION['success_message'];
   unset($_SESSION['success_message']);
}

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">One<b style="color:#dd8ea4">Click</b> Feast</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="about.php">About</a>
         <a href="menu.php">Menu</a>
         <a href="orders.php">Orders</a>
         <a href="contact.php">Contact</a>
      </nav>

      <div class="user_icons" style="display:flex;flex-direction:row;justify-content: center;align-items:center">
      <div class="search-form" id="searchbox" style="display:none">
   <form method="get" action="search.php">
      <input type="text" name="food" required placeholder="search here..." class="box">
      <button type="submit"  class="fas fa-search"></button>
   </form>
         </div>

      <div class="icons" id="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <!-- <a href="search.php"><i class="fas fa-search"></i></a> -->
         <button id="searchBtn"><i class="fas fa-search"></i></button>
        
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      </div>
      <script>
    let searchbox = document.getElementById('searchbox');
    let searchBtn=document.getElementById('searchBtn');

    function showSearchBox() {
      searchBtn.style.display = "none";
    searchbox.style.display = "block";
}

searchBtn.addEventListener('mouseenter', showSearchBox);


    searchbox.addEventListener('mouseenter', function () {
      searchBtn.style.display = "none";
        searchbox.style.display = "block";
    });

    searchbox.addEventListener('mouseleave', function () {
        searchbox.style.display = "none";
        searchBtn.style.display = "block";
    });
</script>


      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">Profile</a>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account">
            <a href="login.php">Login</a> or
            <a href="register.php">Register</a>
         </p> 
         <?php
            }else{
         ?>
            <p class="name">Please login first!</p>
            <a href="login.php" class="btn">Login</a>
         <?php
          }
         ?>
      </div>

   </section>

</header>

