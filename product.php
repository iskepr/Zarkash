<?php
include('layout/actions.php');
include('layout/header.php');

if (isset($_GET['id'])) {
  $ProductID = mysqli_real_escape_string($con, $_GET['id']);
  $query = "SELECT * FROM products WHERE id = '$ProductID'";
  $result = mysqli_query($con, $query);
  if (mysqli_num_rows($result) == 1) {
    $product = mysqli_fetch_assoc($result);
  } else {
    echo "لا يوجد منتج بهذا المعرف.";
    echo '<a href="index.php">الرئيسية</a>';
    exit();
  }
} else {
  echo "لم يتم تحديد معرف المنتج.";
  echo '<a href="index.php">الرئيسية</a>';
  exit();
}

// Cart form add
// Cart form add
if (isset($_POST['addcart'])) {
  $quantity = $_POST['Quantity'];

  // Check if product already exists in cart
  $product_already_in_cart = false;
  foreach ($_SESSION['cart'] as $item) {
    if ($item['id'] == $product['id']) {
      $product_already_in_cart = true;
      break;
    }
  }

  // If product already in cart, don't add it again
  if ($product_already_in_cart) {
    echo "المنتج موجود بالفعل في السلة.";
    echo '<a href="cart.php">عرض السلة</a>';
  } else {
    // Prepare product data for cart
    $cart_item = [
      'id' => $product['id'],
      'name' => $product['name'],
      'price' => $product['price'],
      'img' => $product['img'],
      'quantity' => $quantity
    ];

    // Start session to store cart items
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $cart_item;

    echo "تم إضافة المنتج إلى السلة.";
    echo '<a href="cart.php">عرض السلة</a>';
  }
}

?>

<section>
  <div class="prod">
    <div class="product_box">
      <div class="product_img-box">
        <img src="<?php echo $product['img']; ?>" alt="" />
      </div>
      <div class="product_detail-box">
        <p>
          <?php echo $product['name']; ?><br>
        </p>
        <span class="price">
          جنيه <?php echo $product['price']; ?>
        </span>
        <p>
          <?php
          if ($product["quantity"] > 2) {
            echo 'التوصيل في خلال يومان';
          } elseif ($product["quantity"] > 0 && $product["quantity"] < 3) {
            echo 'التوصيل في خلال يومان';
            echo '<br>';
            echo 'اخر كمية في المخزن';
          } else {
            echo 'التوصيل في خلال اسبوع';
          }
          $category_id = $product['category_id'];
          ?>
        </p>
        <form action="product.php?id=<?php echo $product['id']; ?>" method="post" class="actions">
          <input type="number" name="Quantity" value="1" min="1" required>
          <button type="submit" name="addcart" class="adcart">اضف الي العربة</button>
          <button type="submit" name="like" class="likebut"><span class="material-symbols-outlined">favorite</span></button>
        </form>
      </div>
    </div>
  </div>
  <div id="results" class="gust">
    <section class="container products">
      <div class="container">
        <div class="products-carousel ">
          <div data-aos="fade-left">
            <div data-aos="fade-up">
              <?php
              $products = mysqli_query($con, "SELECT * FROM products WHERE products.category_id = '$category_id'");
              while ($product = mysqli_fetch_array($products)) {
              ?>
                <div class="swiper-slide">
                  <?php
                  if ($product["Time"]) {
                    $productTime = strtotime($product["Time"]);
                    $twoDaysAgo = time() - (2 * 24 * 60 * 60);
                    if ($productTime >= $twoDaysAgo) {
                      echo '<div class="z-1 position-absolute rounded-3 m-3 px-3 border border-dark-subtle">جديد</div>';
                    }
                  }
                  ?>
                  <a href="product.php?id=<?php echo $product['id']; ?>">
                    <div class="card">
                      <div class="card_img">
                        <div class="img"><img src="<?php echo $product["img"]; ?>" alt="image"></div>
                      </div>
                      <div class="card_title">
                        <h6><?php echo $product['name']; ?></h6>
                      </div>
                      <div class="card_footer">
                        <?php if (isset($UserID) && $UserID == 0) { ?>
                          <div class="card-footer">
                            <span class="text-title">الكمية <?php echo $product["quantity"] ?></span>
                            <span class="text-title">شراء <?php echo $product["purchase_price"] ?></span>
                            <span class="text-title">بيع <?php echo $product["price"] ?></span>
                            <span class="text-title">ربح <?php echo $product["price"] -  $product["purchase_price"] ?></span>
                            <div class="card_btn"><a href="editprod.php?id=<?php echo $product["id"] ?>">تعديل</a></div>
                          <?php } ?>
                          <div class="card_price">
                            <h3><?php echo $product['price']; ?> جنية</h3>
                          </div>
                          </div>
                  </a>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <form action="product.php" method="post">
    <button type="submit" name="confirm_order">تأكيد الطلب</button>
  </form>
</section>

<?php include('layout/footer.php'); ?>