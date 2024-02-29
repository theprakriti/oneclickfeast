<!-- Code of Server side Khalit payment and order processing -->
<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
 }else{
    $user_id = '';
    header('location:home.php');
 };

 $message = array();


$response = array();
    $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = "khalti";
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($method=="khalti"){

            
    $payment_amount=($total_price);
    
    if($payment_amount<1000){
       $payment_amount=1000;
    }
    $args = http_build_query(array(
       'token' => $_POST['token'],
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

  
 if($check_cart->rowCount() > 0){

  

      
       


       $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
       $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

       $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
       $delete_cart->execute([$user_id]);

       

       $message['message'] = 'order placed successfully!';

         $message['status'] = 'success';

         $_SESSION['success_message'] = [
            'message' => 'Order placed successfully! Through Online Payment!',
            'cart' => 'Now your cart is empty!'
         ];


         print_r($message);
    
 }
 

 






?>

