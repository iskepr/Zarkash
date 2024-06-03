<?php
include('layout/actions.php');
include('layout/header.php');
if (isset($_SESSION['id'])) {
  $id = $_SESSION['id'];
  $info = mysqli_query($con, "SELECT * FROM users WHERE id='$id'");
  $data = mysqli_fetch_array($info);

  $products = mysqli_query($con, "SELECT * FROM products");
  if (isset($_GET['id'])) {
    $ProductID = mysqli_real_escape_string($con, $_GET['id']);
  }
} else {
  header("Location:welcom.php");
  exit();
}

if (isset($_POST['sndord'])) {
  $sql = "INSERT INTO `order`(UserID, OrderStatus) 
            VALUES ('$id', 1)";
  mysqli_query($con, $sql);

  $check_sql = "SELECT * FROM cart WHERE UserID = '$id'";
  $check_result = mysqli_query($con, $check_sql);
  $row = mysqli_fetch_assoc($check_result);
  $cartid = $row['cartid'];
  $update_sql = "DELETE FROM `cart` WHERE `cart`.`UserID` = '$id'";
  mysqli_query($con, $update_sql);
  header("Location: ordar.php");
} else {
  echo 'فشل الرفع';
}

?>

<head>
  <title>عربة التسوق</title>
</head>
<!-- products section -->
<section class="products_section">
  <div class="heading_container">
    <h2>
      <span class="material-symbols-outlined">shopping_cart</span>عربة التسوق
    </h2>
  </div>
  <div class="container layout_padding">
    <div class="product_container">
      <?php
      $sql = "SELECT * FROM products INNER JOIN cart ON products.id = cart.ProductID
        WHERE cart.UserID = '$id'";
      $products = mysqli_query($con, $sql);
      if ($sql > 0) {
        while ($product = mysqli_fetch_array($products)) {
          echo '
            <div class="card">
                <a href="product.php?id=' . $product["id"] . '">
                    <div class="card-img">
                        <p>Jasmine Boutique</p>
                        <img src="' . $product["img"] . '" />
                    </div>
                    <div class="card-info">
                        <p class="text-title">' . $product["name"] . '</p>
                        <p class="text-body">' . $product["description"] . '</p>
                    </div>
                </a>
                <div class="card-footer">
                    <span class="text-title">جنيه ' . $product["price"] * $product["Quantity"] . '</span>
                    <div class="card-button">
                        <form class="form" action="Cart.php?id=' . $product["id"] . ' " method="post" class="Quantity">
                            <input class="input" class="input class="input"" type="number" name="Quantity" value="' . $product["Quantity"] . '" title="الكمية" min="1" required onchange="submitform class="form"()">
                            <button type="submit" name="upQuantity"><span class="material-symbols-outlined">edit</span></button>
                            
                            <label class="like">
    <input class="input" class="input class="input"" name="like" type="checkbox" onclick="submitform class="form"()">
    <div class="checkmark">
      <svg viewBox="0 0 256 256">
        <rect fill="none" height="256" width="256"></rect>
        <path d="M224.6,51.9a59.5,59.5,0,0,0-43-19.9,60.5,60.5,0,0,0-44,17.6L128,59.1l-7.5-7.4C97.2,28.3,59.2,26.3,35.9,47.4a59.9,59.9,0,0,0-2.3,87l83.1,83.1a15.9,15.9,0,0,0,22.6,0l81-81C243.7,113.2,245.6,75.2,224.6,51.9Z" stroke-width="20px" stroke="#FFF" fill="none"></path>
      </svg>
    </div>
  </label>
                            
                            <button type="submit" name="delCart"><span class="material-symbols-outlined">delete</span></button>
                        </form class="form">
                    </div>
                </div>
            </div>';
        }
      } else {
        echo '<h2> عربة التسوق فارغة تفقد <a href="home.php">المنتجات</a></h2>';
      } ?>
    </div>
    <form class="form" method="post" action="Cart.php">
      <button type="submit" name="sndord">تأكيد الطلب</button>
    </form class="form">

  </div>


</section>
<!-- end products section -->

<?php include('layout/footer.php');
