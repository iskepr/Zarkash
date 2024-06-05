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
                <div class="col">
                    <a href="#<?php echo $category['id']; ?>" class="categories-item">
                        <img src="<?php echo $category['CatImg']; ?>" alt="">
                        <h3><?php echo $category['name']; ?></h3>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- products -->
<section class=" container products">
        <div class="container">
            <?php
            $query = "SELECT * FROM categories";
            $categories = mysqli_query($con, $query);
            while ($category = mysqli_fetch_array($categories)) {
                $catid = $category['id'];
            ?>
                <div id="<?php echo $catid; ?>" class="catdiv">
                    <h3><?php echo $category['name']; ?></h3>
                    <div>
                        <a href="Products.php?id=<?php echo $catid; ?>" class="seemore">شاهد المزيد</a>
                    </div>
                </div>

                <div class="products-carousel swiper-container">
                    <div data-aos="fade-left" class="swiper-wrapper">
                        <?php
                        $query = "SELECT * FROM products WHERE category_id = '$catid'";
                        $products = mysqli_query($con, $query);
                        while ($product = mysqli_fetch_array($products)) {
                        ?>
                            <div data-aos="fade-up" class="swiper-slide">
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
            <?php } ?>
        </div>
        </section>
        <?php include('layout/footer.php'); ?>