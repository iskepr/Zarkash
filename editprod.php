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
      $img = mysqli_real_escape_string($con, $_POST['img']);
      $supplier_id = mysqli_real_escape_string($con, $_POST['supplier_id']);
      $category_id = mysqli_real_escape_string($con, $_POST['category']);

      // استعلام لتحديث بيانات المنتج
      $update_query = "UPDATE `products` SET `name` = '$name', `category_id` = '$category_id', `purchase_price` = '$purchase_price', `price` = '$price', `description` = '$description', `quantity` = '$quantity', `img` = '$img' WHERE `products`.`id` = '$ProductID'";
      mysqli_query($con, $update_query);

      // إعادة التوجيه بعد التحديث
      header("Location: product.php?id=$ProductID");
      exit();
    }
  } else {
    echo "لا يوجد منتج بهذا المعرف.";
    exit();
  }
  if (isset($_POST['del'])) {

    // استعلام لتحديث بيانات المنتج
    $del_query = "DELETE FROM products WHERE products.id = $ProductID";
    mysqli_query($con, $del_query);

    // إعادة التوجيه بعد التحديث
    header("Location: dashboard.php");
    exit();
  }
} else {
  echo "لم يتم تحديد معرف المنتج.";
  exit();
}
?>

<body>
  <section class="dash">
    <div class="upprod">
      <form method="post" class="upform">
        <h2 style="text-align: center;">تعديل المنتج</h2>
        <input type="text" name="name" placeholder="اسم المنتج" value="<?php echo $product['name']; ?>">
        <input type="text" name="price" placeholder="سعر البيع" value="<?php echo $product['price']; ?>">
        <input type="text" name="purchase_price" placeholder="سعر الشراء" value="<?php echo $product['purchase_price']; ?>">
        <input type="text" name="description" placeholder="وصف المنتج" value="<?php echo $product['description']; ?>">
        <input type="number" name="quantity" placeholder="سعر المنتج" value="<?php echo $product['quantity']; ?>">
        <input type="text" name="img" placeholder="رابط الصورة" value="<?php echo $product['img']; ?>"><br>
        <select class="input" name="supplier_id">
          <option selected value="<?php echo $product['supplier_id']; ?>"><?php echo $product['supplier_id']; ?></option>
          <?php while ($supplier = mysqli_fetch_array($suppliers)) {
            echo '<option value="' . $supplier["id"] . '">' . $supplier["name"] . '</option>';
          } ?>
        </select>
        <select class="input" name="category">
          <option selected value="<?php echo $product['category_id']; ?>"><?php echo $product['category_id']; ?></option>
          <?php
          while ($category = mysqli_fetch_array($categories)) {
            echo '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
          } ?>
        </select><br>
        <button type="submit" name="update">تحديث</button>
        <button type="submit" name="del">حذف</button>
      </form>
    </div>
  </section>
  <?php include('layout/footer.php'); ?>