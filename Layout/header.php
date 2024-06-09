<!DOCTYPE html>
<html lang="ar">

<head>
  <title>زركش</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="Style/swiper.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="shortcut icon" href="Assets/Imgs/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="Style/style.css" />
</head>

<body>
  <div class="background"></div>
  <section class="header">
    <header>
      <div class="ul">
        <?php if (isset($UserID) && $UserID == 0) { ?>
          <a href="dashboard.php"><span class="material-symbols-outlined">dashboard</span></a>
        <?php  } elseif (isset($UserID) && $UserID != 0) { ?>
          <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
        <?php } else {
          echo '';
        } ?>
        <span class="material-symbols-outlined" onclick="likemodel()">favorite</span>
        <span class="material-symbols-outlined" onclick="cartmodel()">shopping_cart</span>
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
    <div id="cartmodel" class="model">
      <span class="material-symbols-outlined" onclick="cartmodel()">close</span>
      <table>
        <tr>
          <th>المنتج</th>
          <th>الكمية</th>
          <th>الإجمالي</th>
        </tr>
        <?php
        $total = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
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
        <?php }
        } else {
          echo "لم يتم العثور على عناصر في سلة التسوق.";
        } ?>
      </table>
      <?php
      if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "السلة فارغة.";
        echo '<a href="index.php">الرئيسية</a>';
      }
      ?>
      <div class="cart_total">
        <h3>الإجمالي الكلي: <?php echo $total; ?> جنية</h3>
      </div>
      <form method="post">
        <button type="submit" name="confirm_order">تأكيد الطلب</button>
      </form>
    </div>
    <!-- like model  -->
    <div id="likeModel" class="model">
      <?php
      if (isset($UserID)) {
        $sql = "SELECT * FROM products INNER JOIN Saves ON products.id = Saves.ProductID
        WHERE Saves.UserID = '$UserID'";
        $products = mysqli_query($con, $sql);
        while ($product = mysqli_fetch_array($products)) {
      ?>
          <?php
          if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo "لا يوجد منتجات محفوطة.";
          }
          ?>
          <table>
            <tr>
              <th> </th>
              <th> </th>
            </tr>
            <tr>
              <th>
                <div class="products">
                  <div class="card">
                    <div class="card_img">
                      <div class="img"><img src="<?php echo $product["img"]; ?>" alt="image"></div>
                    </div>
                    <div class="card_title">
                      <h6><?php echo $product['name']; ?></h6>
                    </div>
                    <div class="card_footer">
                      <div class="card_price">
                        <h3><?php echo $product['price']; ?> جنية</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </th>
              <th>
                <form action="index.php?id=<?php echo $product['id']; ?>" method="post">
                  <button type="submit" name="like" value="<?php echo $product['id']; ?>">إزالة</button>
                </form>
              </th>
            </tr>
          </table>
      <?php }
      } ?>
    </div>