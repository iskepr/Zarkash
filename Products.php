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
  <section>
<div class="products"data-aos="fade-left">
    <?php
    while ($product = mysqli_fetch_array($result)) {
    ?>
          <a href="product.php?id=<?php echo $product['id']; ?>" data-aos="fade-up">

            <div class="card">
              <div class="card-img"><img src="<?php echo $product["img"]; ?>" alt="image"></div>
              <?php
              if ($product["Time"]) {
                $productTime = strtotime($product["Time"]);
                $twoDaysAgo = time() - (2 * 24 * 60 * 60);
                if ($productTime >= $twoDaysAgo) {
                  echo '<div class="new lable">جديد</div>';
                }
              }
              ?>
              <div class="card-info">
                <p class="text-title"><?php echo $product['name']; ?></p>
                <!-- <p class="text-body">Product description and details</p> -->
              </div>
              <div class="card-footer">
                <?php if (isset($UserID) && $UserID == 0) { ?>
                  <div class="admininfo">
                    <span class="text-title">المخزون <?php echo $product["quantity"] ?></span>
                    <span class="text-title">شراء <?php echo $product["purchase_price"] ?></span>
                    <span class="text-title">بيع <?php echo $product["price"] ?></span>
                    <span class="text-title">ربح <?php echo $product["price"] -  $product["purchase_price"] ?></span>
                    <div class="card_btn"><a href="editprod.php?id=<?php echo $product["id"] ?>">تعديل</a></div>
                  </div>
                <?php } else { ?>
                  <span class="text-title"><?php echo $product['price']; ?> جنية</span>
                <?php } ?>
                <div class="card-button">
                  <svg class="svg-icon" viewBox="0 0 20 20">
                    <path d="M17.72,5.011H8.026c-0.271,0-0.49,0.219-0.49,0.489c0,0.271,0.219,0.489,0.49,0.489h8.962l-1.979,4.773H6.763L4.935,5.343C4.926,5.316,4.897,5.309,4.884,5.286c-0.011-0.024,0-0.051-0.017-0.074C4.833,5.166,4.025,4.081,2.33,3.908C2.068,3.883,1.822,4.075,1.795,4.344C1.767,4.612,1.962,4.853,2.231,4.88c1.143,0.118,1.703,0.738,1.808,0.866l1.91,5.661c0.066,0.199,0.252,0.333,0.463,0.333h8.924c0.116,0,0.22-0.053,0.308-0.128c0.027-0.023,0.042-0.048,0.063-0.076c0.026-0.034,0.063-0.058,0.08-0.099l2.384-5.75c0.062-0.151,0.046-0.323-0.045-0.458C18.036,5.092,17.883,5.011,17.72,5.011z"></path>
                    <path d="M8.251,12.386c-1.023,0-1.856,0.834-1.856,1.856s0.833,1.853,1.856,1.853c1.021,0,1.853-0.83,1.853-1.853S9.273,12.386,8.251,12.386z M8.251,15.116c-0.484,0-0.877-0.393-0.877-0.874c0-0.484,0.394-0.878,0.877-0.878c0.482,0,0.875,0.394,0.875,0.878C9.126,14.724,8.733,15.116,8.251,15.116z"></path>
                    <path d="M13.972,12.386c-1.022,0-1.855,0.834-1.855,1.856s0.833,1.853,1.855,1.853s1.854-0.83,1.854-1.853S14.994,12.386,13.972,12.386z M13.972,15.116c-0.484,0-0.878-0.393-0.878-0.874c0-0.484,0.394-0.878,0.878-0.878c0.482,0,0.875,0.394,0.875,0.878C14.847,14.724,14.454,15.116,13.972,15.116z"></path>
                  </svg>
                </div>
                <div class="card-button">
                  <svg class="svg-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor" />
                  </svg>
                </div>
              </div>
            </div>
          </a>
      <?php } ?>
</div>
<section>

  <?php
  include('layout/footer.php');
