<?php
include('layout/actions.php');

if (isset($_SESSION['id']) && $_SESSION['id'] == '0') {
  $users = mysqli_query($con, "SELECT * FROM users");
  $products = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC");
  $salse = mysqli_query($con, "SELECT * FROM suppliers");

  $win = mysqli_query($con, "SELECT * FROM suppliers");
  $buy = mysqli_query($con, "SELECT * FROM suppliers");
  $categories = mysqli_query($con, "SELECT * FROM categories");
  $suppliers = mysqli_query($con, "SELECT * FROM suppliers");
} else {
  header("Location:index.php");
  exit();
}
?>

<?php include('layout/header.php');  ?>

<section class="dash">
  <div class="overview">
    <div class="progress-bar">
      <progress value="75" min="0" max="100" style="visibility:hidden;height:0;width:0;">75%</progress>
    </div>
    <div class="card"><span class="material-symbols-outlined">person</span>
      <h4>المستخدمين</h4>
      <h3><?php echo mysqli_num_rows($users); ?></h3>
    </div>
    <div class="card"><span class="material-symbols-outlined">inventory_2</span>
      <h4>المنتجات</h4>
      <h3><?php echo mysqli_num_rows($products); ?></h3>
    </div>
    <div class="card"><span class="material-symbols-outlined">shopping_bag</span>
      <h4>المبيعات</h4>
      <h3><?php
          // استعلام لجلب مجموع سعر المنتجات المباعة في الطلبيات
          $sql = "
    SELECT SUM(oi.Quantity * oi.Price) AS total_sales
    FROM order_items oi
    JOIN orders o ON oi.OrderID = o.OrderID";

          // تنفيذ الاستعلام
          $result = $con->query($sql);

          // التحقق من النتائج
          if ($result->num_rows > 0) {
            // عرض النتيجة
            $row = $result->fetch_assoc();
            echo  $row['total_sales'] . " جنيه";
          } else {
            echo "لا توجد نتائج";
          }
          ?></h3>
    </div>
    <div class="card"><span class="material-symbols-outlined">payments</span>
      <h4>صافي الربج</h4>
      <h3><?php $sql = "
    SELECT SUM((oi.Price - p.purchase_price) * oi.Quantity) AS net_profit
    FROM order_items oi
    JOIN products p ON oi.ProdID = p.id";

          // تنفيذ الاستعلام
          $result = $con->query($sql);

          // التحقق من النتائج
          if ($result->num_rows > 0) {
            // عرض النتيجة
            $row = $result->fetch_assoc();
            echo  $row['net_profit'] . " جنيه";
          } else {
            echo "لا توجد نتائج";
          } ?></h3>
    </div>
    <div class="card"><span class="material-symbols-outlined">sell</span>
      <h4>المشتريات</h4>
      <h3><?php $sql = "
    SELECT SUM(p.purchase_price * oi.Quantity) AS total_purchases
    FROM order_items oi
    JOIN products p ON oi.ProdID = p.id
";

// تنفيذ الاستعلام
$result = $con->query($sql);

// التحقق من النتائج
if ($result->num_rows > 0) {
    // عرض النتيجة
    $row = $result->fetch_assoc();
    echo $row['total_purchases'] . " جنيه";
} else {
    echo "لا توجد نتائج";
} ?></h3>
    </div>
    <div class="card"><span class="material-symbols-outlined">people</span>
      <h4>الموردين</h4>
      <h3><?php echo mysqli_num_rows($suppliers); ?></h3>
    </div>
    <div class="card"><span class="material-symbols-outlined">category</span>
      <h4>الفئات</h4>
      <h3><?php echo mysqli_num_rows($categories); ?></h3>
    </div>
  </div>
  <div class="product_container">
    <div class="upform">
      <div class="upprod">
        <form action="dashboard.php" method="post">
          <h3>رفع مجموعة جديد</h3>
          <input type="text" name="namee" placeholder="اسم المجموعة">
          <input type="file" accept="image/png, image/jpeg, image/jpg" name="img" placeholder="صورة المجموعة"><br>
          <button name="cat" onclick="return">اضافة</button>
        </form>
      </div>

      <div class="upprod">
        <form action="dashboard.php" method="post">
          <h3>رفع مورد جديد</h3>
          <input type="text" name="namee" placeholder="اسم المورد">
          <select name="category">
            <?php while ($category = mysqli_fetch_array($categories)) {
              echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
            } ?>
          </select><br>
          <button name="sup" onclick="return">اضافة</button>
        </form>
      </div>
    </div>
  </div>

  <div class="upprod">
    <form action="dashboard.php" method="post" enctype="multipart/form-data">
      <h3>رفع منتج جديد</h3>
      <input type="text" name="name" placeholder="اسم المنتج" required>
      <input type="text" name="purchase_price" placeholder="سعر الشراء" required>
      <input type="text" name="description" placeholder="وصف المنتج" required>
      <input type="number" name="quantity" placeholder="الكمية" required>
      <input type="file" accept="image/png, image/jpeg, image/jpg" name="img" placeholder="رابط الصورة" required><br>
      <select name="supplier_id" required>
        <?php while ($supplier = mysqli_fetch_array($suppliers)) {
          echo '<option value="' . $supplier["id"] . '">' . $supplier["name"] . '</option>';
        } ?>
      </select>
      <select name="category" required>
        <?php mysqli_data_seek($categories, 0);
        while ($category = mysqli_fetch_array($categories)) {
          echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
        } ?>
      </select><br>
      <button name="prod" type="submit">اضافة</button>
    </form>
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
