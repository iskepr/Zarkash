<?php
include('layout/actions.php');

if (isset($_SESSION['id']) && $_SESSION['id'] == '0') {
  $id = $_SESSION['id'];
  $info = mysqli_query($con, "SELECT * FROM users WHERE id='$id'");
  $data = mysqli_fetch_array($info);

  $products = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC");
  $categories = mysqli_query($con, "SELECT * FROM categories");
  $suppliers = mysqli_query($con, "SELECT * FROM suppliers");
} else {
  header("Location:home.php");
  exit();
}
?>

<?php include('layout/header.php');  ?>

<section class="dash">
  <div class="product_container">
    <div class="upform class=" form"s">
      <div class="upprod">
        <form class="form" action="dashboard.php" method="post" class="upform class=" form"">
          <h3>رفع مجموعة جديد</h3>
          <input class="input" class="input class=" input" class=" input class=" input""" type="text" name="namee" placeholder="اسم المجموعة">
          <input class="input" type="text" name="descriptionn" placeholder="وصف المجموعة">
          <button name="cat" onclick="return">اضافة</button>
        </form class="form">
      </div>
      <div class="upprod">
        <form class="form" action="dashboard.php" method="post" class="upform class=" form"">
          <h3>رفع مورد جديد</h3>
          <input class="input" type="text" name="namee" placeholder="اسم المورد">
          <select class="input class=" input"" name="category">
            <?php while ($category = mysqli_fetch_array($categories)) {
              echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
            } ?>
          </select>
          <button name="sup" onclick="return">اضافة</button>
        </form class="form">
      </div>
    </div>
  </div>

  <div class="upprod">
    <form class="form" action="dashboard.php" method="post" class="upform class=" form"">
      <h3>رفع منتج جديد</h3>
      <input class="input" type="text" name="name" placeholder="اسم المنتج">
      <input class="input" type="text" name="purchase_price" placeholder="سعر الشراء">
      <input class="input" type="text" name="description" placeholder="وصف المنتج">
      <input class="input" type="number" name="quantity" placeholder="الكمية">
      <input class="input" type="text" name="img" placeholder="رابط الصورة">
      <select class="input class=" input"" name="supplier_id">
        <?php while ($supplier = mysqli_fetch_array($suppliers)) {
          echo '<option value="' . $supplier["id"] . '">' . $supplier["name"] . '</option>';
        } ?>
      </select>
      <select class="input class=" input"" name="category">
        <?php mysqli_data_seek($categories, 0);
        while ($category = mysqli_fetch_array($categories)) {
          echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
        } ?>
      </select>
      <button name="prod" onclick="return">اضافة</button>
    </form class="form">
  </div>

  <h3>جميع المنتجات وتعديلها</h3>
  <h3>عدد المنتجات <?php echo mysqli_num_rows($products); ?></h3>
  <div class="product_container" id="products">

    <?php while ($product = mysqli_fetch_array($products)) {
      echo '
            <a href="product.php?id=' . $product["id"] . '">
              <div class="card">
                <div class="card-img">
                  <p>Jasmine Boutique</p>
                  <img src="' . $product["img"] . '" />
                </div>
                <div class="card-info">
                  <p class="text-title">' . $product["name"] . '</p>
                  <p class="text-body">' . $product["description"] . '</p>
                </div>
                <div class="card-footer">
                  <span class="text-title">الكمية ' . $product["quantity"] . '</span>
                  <span class="text-title">شراء ' . $product["purchase_price"] . '</span>
                  <span class="text-title">بيع ' . $product["price"] . '</span>
                  <span class="text-title">ربح ' . $product["price"] - $product["purchase_price"] . '</span>
                <div class="card-button"><a href="editprod.php?id=' . $product["id"] . '">تعديل</a>
                </div>
            
              </div>
              </div>
            </a>';
    }
    ?>
  </div>
</section>
<?php include('layout/footer.php'); ?>

<?php
if (isset($_POST['cat'])) {
  $name = mysqli_real_escape_string($con, $_POST['namee']);
  $description = mysqli_real_escape_string($con, $_POST['descriptionn']);

  $sql = "INSERT INTO categories(name, description) 
    VALUES ('$name', '$description')";

  mysqli_query($con, $sql);
  echo 'نجح الرفع';
}
if (isset($_POST['sup'])) {
  $name = mysqli_real_escape_string($con, $_POST['namee']);
  $categoryy = mysqli_real_escape_string($con, $_POST['category']);

  $sql = "INSERT INTO suppliers(name, category_id, bill) 
    VALUES ('$name', '$categoryy', '')";

  mysqli_query($con, $sql);
  echo 'نجح الرفع';
}

if (isset($_POST['prod'])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $purchase_price = mysqli_real_escape_string($con, $_POST['purchase_price']);
  $price = $purchase_price + ($purchase_price * 25 / 100) + 5 + 20;
  $description = mysqli_real_escape_string($con, $_POST['description']);
  $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
  $img = mysqli_real_escape_string($con, $_POST['img']);
  $supplier_id = mysqli_real_escape_string($con, $_POST['supplier_id']);
  $category_id = mysqli_real_escape_string($con, $_POST['category']);
  $err_s = 0;

  if ($err_s == 0) {
    $sql = "INSERT INTO products(name, price, purchase_price, description, quantity, supplier_id, img, category_id) 
    VALUES ('$name', '$price', '$purchase_price', '$description', '$quantity', '$supplier_id', '$img', '$category_id')";

    mysqli_query($con, $sql);
    echo 'نجح الرفع';
  } else {
    echo 'فشل الرفع';
  }
}
