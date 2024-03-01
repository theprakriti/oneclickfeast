<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}


$product_count=$conn->prepare("SELECT category,COUNT('id') AS product_count FROM `products` GROUP by category");
$product_count->execute();

$products = $product_count->fetchAll(PDO::FETCH_ASSOC);

$categories = array_column($products, 'category');
$product_counts = array_column($products, 'product_count');

$most_searched_products = $conn->prepare("SELECT * FROM `products` ORDER BY search_count DESC LIMIT 5");
$most_searched_products->execute();

$most_searched_products = $most_searched_products->fetchAll(PDO::FETCH_ASSOC);



$most_searched_products_count = array_column($most_searched_products, 'search_count');
$most_searched_products = array_column($most_searched_products, 'name');












?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">Analytics</h1>

   <div class="box-container">
    <div>
    <h1 style="text-align: center;">Mosted Searched Products  <br> Bar Graph</h1>
        <canvas id="bargraph"></canvas>
       
    </div>
        <div >
            <h1 style="text-align: center;">Product Count on Category Basis  <br> Pie Chart</h1>
        <canvas id="piechart"></canvas>
        </div>
   </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const piechart = document.getElementById('piechart');
    const bargraph = document.getElementById('bargraph');

const bargraphdata = {
    labels: <?= json_encode($most_searched_products) ?>,
    datasets: [{
        label: 'Product Count',
        data: <?= json_encode($most_searched_products_count) ?>,
        hoverOffset: 4,
    }]
};

new Chart(bargraph, {
    type: 'bar',
    data: bargraphdata,
});


  const piechartdata = {
  labels: <?= json_encode($categories) ?>,
  datasets: [{
    label: 'Product Count',
    data: <?= json_encode($product_counts) ?>,
    hoverOffset: 4
  }]
};
  new Chart(piechart, {
    type: 'pie',
    data: piechartdata,
  });
</script>

<!-- admin dashboard section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>