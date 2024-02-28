<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      if($address == ''){
         $message[] = 'please add your address!';
      }else{

         if($method=="khalti"){

            
            $payment_amount=($total_price);
            
            if($payment_amount<1000){
               $payment_amount=1000;
            }
            $args = http_build_query(array(
               'token' => 'QUao9cqFzxPgvWJNi9aKac',
               'amount'  =>$payment_amount
             ));
             
             $url = "https://khalti.com/api/v2/payment/verify/";
             
             # Make the call using API.
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_POST, 1);
             curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             
             $headers = ['Authorization: Key test_secret_key_245f5c7d808f47048fc41ec432695f18'];
             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
             
             // Response
             $response = curl_exec($ch);
             $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);
         }
         


         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'order placed successfully!';
      }
      
   }else{
      $message[] = 'your cart is empty';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Khalti CDN -->
   <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>checkout</h3>
   <p><a href="home.php">Home</a> <span> / Checkout</span></p>
</div>

<section class="checkout">

   <h1 class="title">Order Summary</h1>

<form action="checkout.php" method="post" id="cartform">

   <div class="cart-items">
      <h3>Cart Items</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">grand total :</span><span class="price">$<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">veiw cart</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
   <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

   <div class="user-info">
      <h3>your info</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <a href="update_profile.php" class="btn">Update Info</a>
      <h3>Delivery address</h3>
      <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">update address</a>
      <select name="method" class="box" required id="paymentmode">
         <option value="" disabled selected>select payment method --</option>
         <option value="cash on delivery">cash on delivery</option>
         <option value="khalti">Pay with Khalti</option>
      </select>
      <input type="submit" value="place order" id="orderbtn" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">

   </div>

</form>





   
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <?php
$payment_amount=($grand_total);

if($payment_amount<1000){
   $payment_amount=1000;
}


?>


<script>

let cartform = document.getElementById('cartform');
// Example usage:
const url = 'http://localhost/oneclickfeast1/khaltipayment.php';
            let inputs=cartform.querySelectorAll('input');

            const data = Array.from(inputs).reduce((acc, input) => {
    acc[input.name] = input.value;
    return acc;
}, {});

console.log(data);

function sendPostRequest(url, data) {
  // Options for the fetch request
  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json' // Specify the content type as JSON
    },
    body: JSON.stringify(data) // Convert data to JSON string
  };

  // Send the POST request
  return fetch(url, options)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json(); // Parse response body as JSON
    })
    .catch(error => {
      console.error('There was a problem with your fetch operation:', error);
    });
}






   //  console.log(data);



var config = {
    // replace the publicKey with yours
    publicKey: "test_public_key_330813f1d2874d9c9097b4c243ca88d3",
    productIdentity: "1234567890",
    productName: "Dragon",
    productUrl: "http://localhost/oneclickfeast1/checkout.php",
    paymentPreference: [
        "KHALTI",
        "EBANKING",
        "MOBILE_BANKING",
        "CONNECT_IPS",
        "SCT"
    ],
    eventHandler: {
        onSuccess: function(payload) {
            // hit merchant api for initiating verification
            // console.log(payload);
           
            $.post("khaltipayment.php", data, function(data, status){
                    $("#result").html(data);
                });


            sendPostRequest(url, data)
  .then(responseData => {
    console.log('Response:', responseData); // Handle the response data
  });


 


            
        },
        onError: function(error) {
            console.log(error);
        },
        onClose: function() {
            console.log('widget is closing');
        }
    }
};


        var checkout = new KhaltiCheckout(config);
        var btn = document.getElementById("payment-button");
        var paymentmode=document.getElementById('paymentmode');
      
        paymentmode.onchange = function () {
            // minimum transaction amount must be 10, i.e 1000 in paisa.
            if(paymentmode.value=="khalti"){
            checkout.show({amount: <?php echo $payment_amount ?>});
            }
        }
    </script>









<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>



</html>