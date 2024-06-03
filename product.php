<?php
include('layout/actions.php');
include('layout/header.php');
if (isset($_GET['id'])) {
  $ProductID = mysqli_real_escape_string($con, $_GET['id']);
  $query = "SELECT * FROM products WHERE id = '$ProductID'";
  $result = mysqli_query($con, $query);
  $products = mysqli_query($con, "SELECT * FROM products ORDER BY `name` DESC");
  if (mysqli_num_rows($result) == 1) {
    $product = mysqli_fetch_assoc($result);
  } else {
    echo "لا يوجد منتج بهذا المعرف.";
    echo '<a href="home.php">الرئيسية</a>';
    exit();
  }
} else {
  echo "لم يتم تحديد معرف المنتج.";
  echo '<a href="home.php">الرئيسية</a>';
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
          <button type="submit" name="addcard" class="adcart">اضف الي العربة</button>
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
                    $twoDaysAgo = time() - (2 * 24 * 60 * 60); // حساب الوقت قبل يومين بالثواني (2 * 24 ساعة * 60 دقيقة * 60 ثانية)
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

</div>
</section>
<?php include('layout/footer.php');
