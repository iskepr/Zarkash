<?php
include('layout/actions.php');

if (isset($_SESSION['id']) && $_SESSION['id'] == '0') {
  $users = mysqli_query($con, "SELECT * FROM users");
  $products = mysqli_query($con, "SELECT * FROM products");
  $salse = mysqli_query($con, "SELECT * FROM suppliers");
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
    JOIN orders o ON oi.OrderID = o.OrderID
    WHERE o.OrderStatus = 4";

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
      <h3><?php
          $sql = "
    SELECT SUM((oi.Price - p.purchase_price) * oi.Quantity) AS net_profit
    FROM order_items oi
    JOIN products p ON oi.ProdID = p.id 
    WHERE oi.OrderStatus = 4";

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
      <h3><?php
          $sql = "
    SELECT SUM(p.purchase_price * oi.Quantity) AS total_purchases
    FROM order_items oi
    JOIN products p ON oi.ProdID = p.id
    WHERE oi.OrderStatus = 4
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
  <!-- orders  -->
  <div class="orders" id="orders">
    <h2>الطلبيات</h2>
    <table>
      <thead>
        <tr>
          <th>الصورة</th>
          <th>الاسم</th>
          <th>السعر</th>
          <th>العدد</th>
          <th>السعر الكلي</th>
          <th>الحالة</th>
          <th>اسم العميل</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT 
    order_items.OrderID,
    order_items.OrderStatus AS ItemStatus,
    products.name AS ProductName,
    products.img AS ProductImage,
    products.price AS ProductPrice,
    order_items.Quantity,
    order_items.Price * order_items.Quantity AS TotalPrice,
    orders.OrderStatus AS OrderStatus,
    users.UserName
FROM 
    order_items
INNER JOIN 
    products ON order_items.ProdID = products.id
INNER JOIN
    orders ON order_items.OrderID = orders.OrderID
INNER JOIN
    users ON orders.UserID = users.id
ORDER BY 
    order_items.OrderID";

        $products = mysqli_query($con, $sql);

        $current_order = null;
        $order_total = 0;
        $grand_total = 0;

        if (mysqli_num_rows($products) > 0) {
          while ($fetch_cart = mysqli_fetch_assoc($products)) {
            if ($fetch_cart['OrderID'] !== $current_order) {
              if ($current_order !== null) {
        ?>
                <tr>
                  <td colspan="5">المبلغ الكلي للطلب <a href=""><?php echo $current_order; ?></a>:</td>
                  <td><?php echo $order_total; ?> جنيه</td>
                </tr>

            <?php
                $grand_total += $order_total;
                $order_total = 0;
              }
              $current_order = $fetch_cart['OrderID'];
            }
            ?>
            <tr>
              <td><img src="<?php echo $fetch_cart['ProductImage']; ?>"></td>
              <td><?php echo $fetch_cart['ProductName']; ?></td>
              <td><?php echo $fetch_cart['ProductPrice']; ?> جنيه</td>
              <td><?php echo $fetch_cart['Quantity']; ?></td>
              <td><?php echo $fetch_cart['TotalPrice']; ?> جنيه</td>
              <td colspan="6">
                <!-- مربع تحديث الحالة هنا -->
                <form action="dashboard.php" method="post">
                  <input type="hidden" name="OrderID" value="<?php echo $current_order; ?>">
                  <select name="OrderStatus" onchange="this.form.submit()">
                    <option value="1" <?php if ($fetch_cart['OrderStatus'] == 1) echo 'selected'; ?>>جار الشراء</option>
                    <option value="2" <?php if ($fetch_cart['OrderStatus'] == 2) echo 'selected'; ?>>جار التجهيز</option>
                    <option value="3" <?php if ($fetch_cart['OrderStatus'] == 3) echo 'selected'; ?>>جار التوصيل</option>
                    <option value="4" <?php if ($fetch_cart['OrderStatus'] == 4) echo 'selected'; ?>>تم التوصيل</option>
                  </select>
                </form>
              </td>
              <td>
                للمستخدم : <?php echo $fetch_cart['UserName']; ?>
              </td>
            </tr>
          <?php
            $order_total += $fetch_cart['TotalPrice'];
          }
          if ($current_order !== null) {
          ?>
            <tr>
              <td colspan="5">المبلغ الكلي للطلب <a href=""><?php echo $current_order; ?></a>:</td>
              <td><?php echo $order_total; ?> جنيه</td>
            </tr>
            <tr>
              <td colspan="6">
                <!-- مربع تحديث الحالة هنا -->
                <form action="update_order_status.php" method="post">
                  <input type="hidden" name="OrderID" value="<?php echo $current_order; ?>">
                  <select name="OrderStatus" onchange="this.form.submit()">
                    <option selected disabled value="<?php echo $fetch_cart['OrderStatus']; ?>"><?php echo $fetch_cart['OrderStatus']; ?></option>
                    <option value="1" <?php if ($fetch_cart['OrderStatus'] == 1) echo 'selected'; ?>>جار الشراء</option>
                    <option value="2" <?php if ($fetch_cart['OrderStatus'] == 2) echo 'selected'; ?>>جار التجهيز</option>
                    <option value="3" <?php if ($fetch_cart['OrderStatus'] == 3) echo 'selected'; ?>>جار التوصيل</option>
                    <option value="4" <?php if ($fetch_cart['OrderStatus'] == 4) echo 'selected'; ?>>تم التوصيل</option>
                  </select>
                </form>
              </td>
            </tr>
        <?php
            $grand_total += $order_total;
          }
        } else {
          echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">لا يوجد طلبات</td></tr>';
        }
        ?>
        <tr class="table-bottom">
          <td colspan="5">المبلغ الإجمالي:</td>
          <td> <?php echo $grand_total; ?> جنيه </td>
        </tr>
      </tbody>
    </table>


  </div>
  <div class="product_container">
    <div class="upform">
      <div class="upprod">
        <form action="dashboard.php" method="post" enctype="multipart/form-data">
          <h3>رفع فئة جديد</h3>
          <label for="name">اسم المجموعة</label>
          <input type="text" id="name" name="name" placeholder="اسم المجموعة" required>

          <label for="img">صورة المجموعة</label>
          <input type="file" id="img" accept="image/png, image/jpeg, image/jpg" name="img" placeholder="صورة المجموعة" required><br>

          <button type="submit" name="cat">اضافة</button>
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
      <input type="text" name="description" placeholder="وصف المنتج" required style="width: 45%;">
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
<script>
  function submitForm() {
    document.getElementById("orderForm").submit();
  }
</script>
<?php include('layout/footer.php'); ?>

<?php

if (isset($_POST['sup'])) {
  $name = mysqli_real_escape_string($con, $_POST['namee']);
  $categoryy = mysqli_real_escape_string($con, $_POST['category']);

  $sql = "INSERT INTO suppliers(name, category_id, bill) 
    VALUES ('$name', '$categoryy', '')";

  mysqli_query($con, $sql);
  echo 'نجح الرفع';
}

if (isset($_POST['OrderStatus'])) {
  $order_id = mysqli_real_escape_string($con, $_POST['OrderID']);
  $status = mysqli_real_escape_string($con, $_POST['OrderStatus']);

  $sql = "UPDATE order_items SET OrderStatus = '$status' WHERE OrderItemID = $order_id";

  if (mysqli_query($con, $sql)) {
    echo 'نجح الرفع';
  } else {
    echo 'فشل الرفع';
  }
}
