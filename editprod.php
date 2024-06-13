<?php
include('layout/actions.php');
include('layout/header.php');

if (isset($_GET['id'])) {
  $ProductID = mysqli_real_escape_string($con, $_GET['id']);

  // استعلام لاسترداد بيانات المنتج
  $query = "SELECT * FROM products WHERE id = '$ProductID'";
  $result = mysqli_query($con, $query);

  $suppliers = mysqli_query($con, "SELECT * FROM suppliers");
  $categories = mysqli_query($con, "SELECT * FROM categories");

  if (mysqli_num_rows($result) == 1) {
    $product = mysqli_fetch_assoc($result);

    // التحقق من تم النقر على زر التعديل
    if (isset($_POST['update'])) {
      $name = mysqli_real_escape_string($con, $_POST['name']);
      $price = mysqli_real_escape_string($con, $_POST['price']);
      $purchase_price = mysqli_real_escape_string($con, $_POST['purchase_price']);
      $description = mysqli_real_escape_string($con, $_POST['description']);
      $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
      $supplier_id = mysqli_real_escape_string($con, $_POST['supplier_id']);
      $category_id = mysqli_real_escape_string($con, $_POST['category']);

      // معالجة الصورة المرفوعة
      if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['png', 'jpeg', 'jpg'];
        if (!in_array($img_extension, $allowed_extensions)) {
          die('امتداد الملف غير مسموح.');
        }

        // تحديث عداد الصور
        $counterFile = 'counter.txt';
        $currentCounter = file_exists($counterFile) ? file_get_contents($counterFile) : 0;
        $currentCounter = intval($currentCounter);
        $newCounter = $currentCounter + 1;
        file_put_contents($counterFile, $newCounter);
        $new_img_name = "منتج_زركش" . $newCounter . "." . $img_extension;
        $prodimg = "Assets/imgs/" . $new_img_name;

        // نقل الملف المرفوع إلى المسار المطلوب
        if (!move_uploaded_file($img_tmp_name, $prodimg)) {
          die('فشل في رفع الملف.');
        }
      } else {
        // استخدم الصورة القديمة إذا لم يتم رفع صورة جديدة
        $prodimg = $product['img'];
      }

      // استعلام لتحديث بيانات المنتج
      $update_query = "UPDATE `products` SET `name` = '$name', `category_id` = '$category_id', `purchase_price` = '$purchase_price', `price` = '$price', `description` = '$description', `quantity` = '$quantity', `img` = '$prodimg' WHERE `products`.`id` = '$ProductID'";
      mysqli_query($con, $update_query);

      // إعادة التوجيه بعد التحديث
      header("Location: product.php?id=$ProductID");
      exit();
    }
  } else {
    echo "لا يوجد منتج بهذا المعرف.";
  }

  if (isset($_POST['del'])) {
    // استعلام لحذف المنتج
    $del_query = "DELETE FROM products WHERE products.id = $ProductID";
    mysqli_query($con, $del_query);

    // إعادة التوجيه بعد الحذف
    header("Location: index.php");
    exit();
  }
} else {
  echo "لم يتم تحديد معرف المنتج.";
}
?>

<body>
  <section class="dash">
    <div class="upprod">
      <form method="post" class="upform" enctype="multipart/form-data">
        <h2 style="text-align: center;">تعديل المنتج</h2>
        <input type="text" name="name" placeholder="اسم المنتج" value="<?php echo htmlspecialchars($product['name']); ?>">
        <input type="text" name="price" placeholder="سعر البيع" value="<?php echo htmlspecialchars($product['price']); ?>">
        <input type="text" name="purchase_price" placeholder="سعر الشراء" value="<?php echo htmlspecialchars($product['purchase_price']); ?>">
        <input type="text" name="description" placeholder="وصف المنتج" value="<?php echo htmlspecialchars($product['description']); ?>">
        <input type="number" name="quantity" placeholder="كمية المنتج" value="<?php echo htmlspecialchars($product['quantity']); ?>">

        <?php if (!empty($product['img'])) : ?>
          <img src="<?php echo htmlspecialchars($product['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="صورة الفئة" style="max-width: 200px; max-height: 200px;"><br><br>
          <input type="hidden" name="current_img" value="<?php echo htmlspecialchars($product['img'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <input type="file" id="img" accept="image/png, image/jpeg, image/jpg" name="img"><br><br>

        <select class="input" name="supplier_id">
          <option selected value="<?php echo htmlspecialchars($product['supplier_id']); ?>"><?php echo htmlspecialchars($product['supplier_id']); ?></option>
          <?php while ($supplier = mysqli_fetch_array($suppliers)) { ?>
            <option value="<?php echo htmlspecialchars($supplier["id"]); ?>"><?php echo htmlspecialchars($supplier["name"]); ?></option>
          <?php } ?>
        </select>
        <select class="input" name="category">
          <option selected value="<?php echo htmlspecialchars($product['category_id']); ?>"><?php echo htmlspecialchars($product['category_id']); ?></option>
          <?php while ($category = mysqli_fetch_array($categories)) { ?>
            <option value="<?php echo htmlspecialchars($category["id"]); ?>"><?php echo htmlspecialchars($category["name"]); ?></option>
          <?php } ?>
        </select><br>
        <button type="submit" name="update">تحديث</button>
        <button type="submit" name="del">حذف</button>
      </form>
    </div>
  </section>
  <?php include('layout/footer.php'); ?>
</body>