<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';

}


if(isset($_GET['btnSortOrder'])){

   

  
   $order_type = $_GET['order_type'];
   $order_value = $_GET['order_value'];
   $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY $order_type $order_value");
   $select_orders->execute();



}else{
   $select_orders = $conn->prepare("SELECT * FROM `orders`");
   $select_orders->execute();
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      .form-select{
         padding: 10px;
         margin: 10px 0;
         border: 1px solid #333;
         border-radius: 5px;
      }

      .form-label{
         font-size: 1.4rem;
         font-weight: 500;
      }
   </style>

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed orders section starts  -->

<section class="placed-orders">

  <div style="display: flex; justify-content: space-between;">
  <h1 class="heading">placed orders</h1>

  <div>
 <div style="display: flex;justify-content: space-between;">
 <h2>Sorting</h2>
   <h2>
      <?php

      if(isset($_GET['order_type']) && isset($_GET['order_value'])){
         echo $_GET['order_type'].' : '.$_GET['order_value'];
      }
      ?></h2>
 </div>
    <form action="" method="get" class="box" style="display: flex; align-items: center;margin-block: 10px;">
        <div class="form-group">
            <label for="search" class="form-label" >Select Type</label><br>
            <select class="form-select drop-down" name="order_type" id="order_type">
                <option value=""  disabled   >select type</option>
                <option value="user_id"  <?php isset($_GET['order_type']) && $_GET['order_type']=='user_id' ? 'selected': '';?> >user id</option>
                <option value="name"  <?php isset($_GET['order_type']) && $_GET['order_type']=='name' ? 'selected': '';?>>name</option>
                <option value="number" <?php isset($_GET['order_type']) && $_GET['order_type']=='number' ? 'selected': '';?>>number</option>
                <option value="total_price" <?php isset($_GET['order_type']) && $_GET['order_type']=='total_price' ? 'selected': '';?>>total price</option>
            </select>
        </div>

        <div class="form-group">
            <label for="" class="form-label">Select Value</label><br>
            <select class="form-select" name="order_value" id="">
                <option value=""   disabled>select value</option>
                <option value="ASC" <?php isset($_GET['order_value']) && $_GET['order_value']=='ASC' ? 'selected': '';?>>Ascending</option>
                <option value="DESC"  <?php isset($_GET['order_value']) && $_GET['order_value']=='DESC' ? 'selected': '';?>>Descending</option>
            </select>
        </div>

        <div>
            <input type="submit" name="btnSortOrder" value="sort" class="btn">
        </div>
    </form>
   
</div>


  </div>
   <div class="box-container">

   <?php
      
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> total price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">pending</option>
            <option value="completed">completed</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="update" class="option-btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </div>
      </form>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>

<!-- placed orders section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>