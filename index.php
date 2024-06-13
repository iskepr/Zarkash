<?php
include('layout/actions.php');
include('layout/header.php');
?>

</section>
<section class="paner">
  <div class="paner">
    <img class="pc" src="Assets/Imgs/paner.jpg" alt="البانار">
    <img class="ph" src="Assets/Imgs/panerph.jpg" alt="البانار">
  </div>
</section>

<!-- categorys -->
<section id="categories">
  <div class="container">
    <div class="row">
      <?php
      $query = "SELECT * FROM categories";
      $categories = mysqli_query($con, $query);
      while ($category = mysqli_fetch_array($categories)) {
      ?>
        <div class="cat">
          <a href="products.php?id=<?php echo $category['id']; ?>" class="categories-item">
            <img src="<?php echo $category['CatImg']; ?>" alt="">
            <h3><?php echo $category['name']; ?></h3>
          </a>
          <?php if (isset($UserID) && $UserID == 0) { ?>
            <a href="editcategory.php?id=<?php echo $category['id']; ?>" class="icon">تعديل</a>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
<!-- products -->
<section class="products">
  <div class="products" data-aos="fade-left">
    <?php
    $query = "SELECT * FROM `products` ORDER BY `products`.`id` DESC";
    $products = mysqli_query($con, $query);
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
</section>
<?php include('layout/footer.php'); ?>