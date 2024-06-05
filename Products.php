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

<section class="my-5 overflow-hidden">
  <div class="container my-5 py-5">
    <div class="isotope-container row">
      <?php
      while ($product = mysqli_fetch_assoc($result)) {
      ?>
        <div class="item col-md-4 col-lg-3 my-4">
          <?php
          if ($product["Time"]) {
            $productTime = strtotime($product["Time"]);
            $twoDaysAgo = time() - (2 * 24 * 60 * 60); // حساب الوقت قبل يومين بالثواني (2 * 24 ساعة * 60 دقيقة * 60 ثانية)
            if ($productTime >= $twoDaysAgo) {
              echo '<div class="z-1 position-absolute rounded-3 m-3 px-3 border border-dark-subtle">جديد</div>';
            }
          }
          ?>
          <div class="card position-relative">
            <a href="product.php?id=<?php echo $product['id']; ?>"><img src="<?php echo $product["img"]; ?>" class="img-fluid rounded-4" alt="image"></a>
            <div class="card-body p-0">
              <a href="product.php?id=<?php echo $product['id']; ?>">
                <h3 class="card-title pt-4 m-0"><?php echo $product['name']; ?></h3>
              </a>
              <div class="card-text">
                <span class="rating secondary-font"><?php echo $product['description']; ?></span>
                <h3 class="secondary-font text-primary">جنيه <?php echo $product['price']; ?></h3>
                <div class="d-flex flex-wrap mt-3">
                  <form action="index.php?id=<?php echo $product['id']; ?>" method="post" id="myForm">
                    <button type="submit" name="addCart" class="text-uppercase m-0"><span class="material-symbols-outlined">shopping_cart</span></button>
                    <button type="submit" name="like" class="btn-wishlist px-4 pt-3 "><span class="material-symbols-outlined">favorite</span></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <section>

    <?php
    include('layout/footer.php');
