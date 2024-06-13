<?php
ob_start();
include('layout/actions.php');
include('layout/header.php');

if (isset($_GET['id'])) {
  $CatID = mysqli_real_escape_string($con, $_GET['id']);

  // استعلام لاسترداد بيانات الفئة
  $query = "SELECT * FROM categories WHERE id = '$CatID'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) == 1) {
    $category = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['update'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $catimg = $category['CatImg'];

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
          $new_img_name = "فئة_زركش" . $newCounter . "." . $img_extension;
          $catimg = "Assets/imgs/" . $new_img_name;

          // نقل الملف المرفوع إلى المسار المطلوب
          if (!move_uploaded_file($img_tmp_name, $catimg)) {
            die('فشل في رفع الملف.');
          }
        }

        // استعلام لتحديث بيانات الفئة
        $update_query = "UPDATE `categories` SET `name` = '$name', `CatImg` = '$catimg' WHERE `id` = '$CatID'";
        mysqli_query($con, $update_query);

        // إعادة التوجيه بعد التحديث
        header("Location: products.php?id=$CatID");
        exit();
      }

      if (isset($_POST['del'])) {
        // استعلام لحذف الفئة
        $del_query = "DELETE FROM categories WHERE id = $CatID";
        mysqli_query($con, $del_query);

        // إعادة التوجيه بعد الحذف
        header("Location: index.php");
        exit();
      }
    }
  } else {
    echo "لا توجد فئة بهذا المعرف.";
  }
} else {
  echo "لم يتم تحديد معرف الفئة.";
}

ob_end_flush();
?>

<body>
  <section class="dash">
    <div class="upprod">
      <form method="post" class="upform" enctype="multipart/form-data">
        <h2 style="text-align: center;">تعديل الفئة</h2>
        <input type="text" name="name" placeholder="اسم الفئة" value="<?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>
        <?php if (!empty($category['CatImg'])) : ?>
          <img src="<?php echo htmlspecialchars($category['CatImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="صورة الفئة" style="max-width: 200px; max-height: 200px;"><br><br>
          <input type="hidden" name="current_img" value="<?php echo htmlspecialchars($category['CatImg'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <input type="file" id="img" accept="image/png, image/jpeg, image/jpg" name="img"><br><br>
        <button type="submit" name="update">تحديث</button>
        <button type="submit" name="del">حذف</button>
      </form>
    </div>
  </section>
  <?php include('layout/footer.php'); ?>
</body>