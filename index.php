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
<!-- dashboard -->
<?php if (isset($UserID) && $UserID == 0) { ?>
    <section class="dash">
        <div class="product_container">
            <div class="upforms">
                <div class="upprod">
                    <form action="dashboard.php" method="post" class="upform">
                        <h3>رفع مجموعة جديد</h3>
                        <input type="text" name="namee" placeholder="اسم المجموعة">
                        <input type="text" name="descriptionn" placeholder="وصف المجموعة">
                        <button name="cat" onclick="return">اضافة</button>
                    </form>
                </div>
                <div class="upprod">
                    <form action="dashboard.php" method="post" class="upform">
                        <h3>رفع مورد جديد</h3>
                        <input type="text" name="namee" placeholder="اسم المورد">
                        <select class="input" name="category">
                            <?php while ($category = mysqli_fetch_array($categories)) {
                                echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
                            } ?>
                        </select>
                        <button name="sup" onclick="return">اضافة</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="upprod">
            <form action="dashboard.php" method="post" class="upform">
                <h3>رفع منتج جديد</h3>
                <input type="text" name="name" placeholder="اسم المنتج">
                <input type="text" name="purchase_price" placeholder="سعر الشراء">
                <input type="text" name="description" placeholder="وصف المنتج">
                <input type="number" name="quantity" placeholder="الكمية">
                <input type="text" name="img" placeholder="رابط الصورة">
                <select class="input" name="supplier_id">
                    <?php while ($supplier = mysqli_fetch_array($suppliers)) {
                        echo '<option value="' . $supplier["id"] . '">' . $supplier["name"] . '</option>';
                    } ?>
                </select>
                <select class="input" name="category">
                    <?php mysqli_data_seek($categories, 0);
                    while ($category = mysqli_fetch_array($categories)) {
                        echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
                    } ?>
                </select>
                <button name="prod" onclick="return">اضافة</button>
            </form>
        </div>

        <h3>جميع المنتجات وتعديلها</h3>
        <h3>عدد المنتجات <?php echo mysqli_num_rows($products); ?></h3>
        </div>
    </section>
<?php } else {
    echo '';
} ?>
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
<section class="container products">
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
                                            <!-- <form action="index.php?id=<?php // echo $product['id']; 
                                                                            ?>" method="post" id="myForm">
                                                    <input class="input" hidden type="number" name="price" value="<?php // echo $product['price']; 
                                                                                                                    ?>">
                                                    <button type="submit" name="addCart" class="card-btn"><span class="material-symbols-outlined">shopping_cart</span></button>
                                                    <button type="submit" name="like" class="card-btn"><span class="material-symbols-outlined">favorite</span></button>
                                                </form> -->
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