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
        <?php if (isset($UserID) && $UserID == 0) { ?>
          <div class="title">
            <h2><?php echo $product['name']; ?></h2>
            <h4>المخزون <?php echo $product["quantity"] ?></h4>
            <h4>شراء <?php echo $product["purchase_price"] ?></h4>
            <h4>ربح <?php echo $product["price"] -  $product["purchase_price"] ?></h4>
            <a class="icon" href="editprod.php?id=<?php echo $product["id"] ?>"><button>تعديل</button></a>
          </div>
        <?php } ?>
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
    <div class="products" data-aos="fade-left">
      <?php
      $products = mysqli_query($con, "SELECT * FROM products WHERE products.category_id = '$category_id' LIMIT 6");
      while ($product = mysqli_fetch_array($products)) {
      ?>
        <!-- product -->
        <a class="prodcard" href="product.php?id=<?php echo $product['id']; ?>" data-aos="fade-up">
          <div class="front">
            <?php
            if ($product["Time"]) {
              $productTime = strtotime($product["Time"]);
              $twoDaysAgo = time() - (2 * 24 * 60 * 60);
              if ($productTime >= $twoDaysAgo) {
                echo '<div class="new lable">جديد</div>';
              }
            }
            ?>
            <div class="img"><img src="<?php echo $product['img']; ?>" alt=""></div>
            <div class="title">
              <h3><?php echo $product['name']; ?></h3>
              <h4><?php echo $product['price']; ?> جنية</h4>
            </div>
          </div>

          <div class="back">
            <?php if (isset($UserID) && $UserID == 0) { ?>
              <div class="title">
                <h2><?php echo $product['name']; ?></h2>
                <h4>المخزون <?php echo $product["quantity"] ?></h4>
                <h4>شراء <?php echo $product["purchase_price"] ?></h4>
                <h4>بيع <?php echo $product["price"] ?></h4>
                <h4>ربح <?php echo $product["price"] -  $product["purchase_price"] ?></h4>
              </div>
            <?php } else { ?>
              <div class="title">
                <h2><?php echo $product['name']; ?></h2>
              </div>
              <div class="but">
                <form class="but" action="index.php?id=<?php echo $product['id']; ?>" method="post">
                  <input type="hidden" name="Quantity" value="1" min="1" required>
                  <button type="submit" name="addcart" class="icon material-symbols-outlined">shopping_cart</button>
                  <button type="submit" name="like" class="icon material-symbols-outlined">favorite</button>
                </form>
              </div>
            <?php } ?>
          </div>
        </a>
      <?php } ?>
    </div>
  </div>
</section>

<?php include('layout/footer.php'); ?>