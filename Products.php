<?php
include('layout/actions.php');
include('layout/header.php');
if (isset($_GET['id'])) {
  $CategoryID = mysqli_real_escape_string($con, $_GET['id']);

  // استعلام لاسترداد اسم الفئة
  $categoryQuery = "SELECT name FROM categories WHERE id = '$CategoryID'";
  $categoryResult = mysqli_query($con, $categoryQuery);
  $category = mysqli_fetch_assoc($categoryResult);

  // استعلام لاسترداد المنتجات التي تنتمي للفئة المحددة
  $query = "SELECT * FROM products WHERE category_id = '$CategoryID'";
  $result = mysqli_query($con, $query);

  // التحقق مما إذا كانت هناك منتجات في الفئة المحددة
  if (mysqli_num_rows($result) > 0) {
    // طباعة اسم الفئة
    echo "<h2>أسم الفئة: {$category['name']}</h2>";
  } else {
    echo "لا يوجد منتجات في هذه الفئة.";
    echo '<a href="home.php">الرئيسية</a>';
    exit();
  }
} else {
  echo "لم يتم تحديد معرف الفئة.";
  echo '<a href="home.php">الرئيسية</a>';
  exit();
}
?>
<div class="products-carousel">
  <div data-aos="fade-left">
    <?php
    while ($product = mysqli_fetch_array($result)) {
    ?>
      <section>
        <div data-aos="fade-up">
          <a href="product.php?id=<?php echo $product['id']; ?>">
            <div class="card">
              <div class="card_img">
                <div class="img"><img src="<?php echo $product["img"]; ?>" alt="image"></div>
              </div>
              <?php
              if ($product["Time"]) {
                $productTime = strtotime($product["Time"]);
                $twoDaysAgo = time() - (2 * 24 * 60 * 60);
                if ($productTime >= $twoDaysAgo) {
                  echo '<div class="new lable">جديد</div>';
                }
              }
              ?>
              <div class="card_title">
                <h6><?php echo $product['name']; ?></h6>
              </div>
              <div class="card_footer">
                <?php if (isset($UserID) && $UserID == 0) { ?>
                  <div class="admininfo">
                    <span class="text-title">المخزون <?php echo $product["quantity"] ?></span>
                    <span class="text-title">شراء <?php echo $product["purchase_price"] ?></span>
                    <span class="text-title">بيع <?php echo $product["price"] ?></span>
                    <span class="text-title">ربح <?php echo $product["price"] -  $product["purchase_price"] ?></span>
                    <div class="card_btn"><a href="editprod.php?id=<?php echo $product["id"] ?>">تعديل</a></div>
                  </div>
                <?php } else { ?>
                  <div class="card_price">
                    <h3><?php echo $product['price']; ?> جنية</h3>
                  </div>
                <?php } ?>
              </div>
            </div>
          </a>
        </div>
      <?php } ?>
  </div>
</div>
<section>

  <?php
  include('layout/footer.php');
