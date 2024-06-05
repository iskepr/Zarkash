<?php

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  echo "السلة فارغة.";
  echo '<a href="home.php">الرئيسية</a>';
  exit();
}

if (isset($_POST['confirm_order'])) {
  if (isset($_SESSION['cart'])) {
    $user_id = 1; // يجب أن يكون معرّف المستخدم ديناميكياً (مثلاً من جلسة المستخدم)
    $order_query = "INSERT INTO orders (user_id, created_at) VALUES ('$user_id', NOW())";
    if (mysqli_query($con, $order_query)) {
      $order_id = mysqli_insert_id($con);
      foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        mysqli_query($con, $order_item_query);
      }
      unset($_SESSION['cart']);
      echo "تم تأكيد الطلب.";
      echo '<a href="home.php">الرئيسية</a>';
      exit();
    } else {
      echo "فشل في تأكيد الطلب.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
  <title>زركش</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="Style/style.css" />
  <link rel="stylesheet" href="Style/swiper.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="shortcut icon" href="Assets/Imgs/logo.png" type="image/x-icon">
</head>

<body>
  <div class="background"></div>
  <section class="header">
    <header>
      <div class="ul">
        <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
        <span class="material-symbols-outlined">favorite</span>
        <span class="material-symbols-outlined" onclick="carthmodel()">shopping_cart</span>
      </div>
      <div class="logo">
        <a href="index.php"><img src="Assets/Imgs/logo.png" alt="زركش"></a>
      </div>
      <div class="search">
        <span class="material-symbols-outlined" onclick="searchmodel()">search</span>
    </header>

    <!-- search model -->
    <div id="searchModel" class="searchmodel">
      <div class="search_container">
        <h1>البحث عن المنتجات</h1>
        <input type="text" name="query" id="searchQuery" placeholder="ادخل اسم المنتج">
        <div id="results"></div>
      </div>
    </div>

    <!-- cart model -->
    <div id="cartmodel" class="cartmodel">
      <?php
      if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "السلة فارغة.";
        echo '<a href="home.php">الرئيسية</a>';
      }
      ?>

<div id="cartmodel" class="cartmodel">
  <section>
          <span class="material-symbols-outlined" onclick="carthmodel()">close</span>
          <table>
            <tr>
              <th>المنتج</th>
              <th>الكمية</th>
              <th>الإجمالي</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
              ?>
              <tr class="cart">
                <td>
                  <div class="products">
                    <div class="card">
                      <div class="card_img">
                        <div class="img"><img src="<?php echo $item["img"]; ?>" alt="image"></div>
                      </div>
                      <div class="card_title">
                        <h6><?php echo $item['name']; ?></h6>
                      </div>
                      <div class="card_footer">
                        <div class="card_price">
                          <h3><?php echo $item['price']; ?> جنية</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <input type="number" name="Quantity" value="<?php echo $item['quantity']; ?>" min="1" required onchange="updateCart(<?php echo $item['id']; ?>, this.value)">
                  <button onclick="deleteFromCart(<?php echo $item['id']; ?>)"><span class="material-symbols-outlined">delete</span></button>
                </td>
                <td>
                  <h4>جنية <?php echo $subtotal; ?></h4>
                </td>
              </tr>
            <?php } ?>
          </table>
          <div class="cart_total">
            <h3>الإجمالي الكلي: <?php echo $total; ?> جنية</h3>
          </div>
          <form action="cart.php" method="post">
            <button type="submit" name="confirm_order">تأكيد الطلب</button>
          </form>
        </section>
      </div>

      <script>
        function updateCart(productId, quantity) {
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "update_cart.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
              location.reload();
            }
          };
          xhr.send("updateCart=1&ProductID=" + productId + "&Quantity=" + quantity);
        }

        function deleteFromCart(productId) {
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "update_cart.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
              location.reload();
            }
          };
          xhr.send("delCart=1&ProductID=" + productId);
        }
      </script>