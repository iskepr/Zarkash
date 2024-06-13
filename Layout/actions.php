<?php
session_start();
// conection
$con = mysqli_connect('localhost', 'root', '', 'zarkash');
if (!$con) {
  die('error' . mysqli_connect_error());
}
if (isset($_SESSION['id'])) {
  $UserID = $_SESSION['id'];
  $info = mysqli_query($con, "SELECT * FROM users WHERE id='$UserID'");
  $data = mysqli_fetch_array($info);

  $products = mysqli_query($con, "SELECT * FROM products");
}
// Saves form 

if (isset($_POST['like'])) {
  if (isset($UserID)) {
    if (isset($_GET['id'])) {
      $ProductID = mysqli_real_escape_string($con, $_GET['id']);
      $check_sql_like = "SELECT * FROM Saves WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
      $check_result_like = mysqli_query($con, $check_sql_like);
    }
    if (mysqli_num_rows($check_result_like) > 0) {
      $row = mysqli_fetch_assoc($check_result_like);
      $SavedID = $row['SavedID'];
      $update_sql = "DELETE FROM `Saves` WHERE `Saves`.`SavedID` = '$SavedID'";
      mysqli_query($con, $update_sql);
      echo '<div class="masseg"><h4>تم حذف من المفضلة</h4></div>';
    } else {
      $sql = "INSERT INTO `Saves`(UserID, ProductID) 
    VALUES ('$UserID', '$ProductID')";
      mysqli_query($con, $sql);
      echo '<div class="masseg"><h4>تم الاضافة الي المفضلة</h4></div>';
    }
  } else {
    echo '<div class="masseg"><h4>يجب عليك التسجيل اولا</h4></div>';
  }
}

// Cart form add
if (isset($_POST['addcart'])) {
  if (isset($_GET['id'])) {
    $ProductID = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM products WHERE id = '$ProductID'";
    $result = mysqli_query($con, $query);
    $product = mysqli_fetch_assoc($result);
  }
  $quantity = $_POST['Quantity'];

  // Check if product already exists in cart
  $product_already_in_cart = false;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      if ($item['id'] == $product['id']) {
        $product_already_in_cart = true;
        break;
      }
    }
  }

  // If product already in cart, don't add it again
  if ($product_already_in_cart) {
    echo '<div class="masseg"><h4>المنتج موجود بالفعل في السلة.</h4></div>';
  } else {
    // Prepare product data for cart
    $cart_item = [
      'id' => $product['id'],
      'name' => $product['name'],
      'price' => $product['price'],
      'img' => $product['img'],
      'quantity' => $quantity
    ];

    // Start session to store cart items
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $cart_item;

    echo '<div class="masseg"><h4>تم إضافة المنتج إلى السلة.</h4></div>';
  }
}

// Cart form delete
if (isset($_POST['delCart'])) {
  $ProductID = $_POST['ProductID'];

  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $ProductID) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }
  $_SESSION['cart'] = array_values($_SESSION['cart']);
  echo '<div class="masseg"><h4>تم ازالة المنتج من لسلة </h4></div>';
}
// Cart form update quante
if (isset($_POST['updateCart'])) {
  $ProductID = $_POST['ProductID'];
  $Quantity = $_POST['Quantity'];

  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $ProductID) {
      $item['quantity'] = $Quantity;
      break;
    }
  }
  echo '<div class="masseg"><h4>تم تعديل المنتج في لسلة </h4></div>';
}


// Send Order
if (isset($_POST['confirm_order'])) {
  if (isset($_SESSION['cart'])) {

    // إدراج الطلب في جدول orders
    $order_query = "INSERT INTO orders (UserID, OrderDate) VALUES ('$UserID', NOW())";
    if (mysqli_query($con, $order_query)) {
      // الحصول على معرف الطلب الذي تم إدخاله
      $order_id = mysqli_insert_id($con);
      // إدراج عناصر الطلب في جدول order_items
      foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        // التأكد من أن المنتج موجود في جدول products
        $check_product_query = "SELECT id FROM products WHERE id = '$product_id'";
        $product_result = mysqli_query($con, $check_product_query);
        if (mysqli_num_rows($product_result) > 0) {
          // إذا كان المنتج موجودًا، قم بإدخاله في جدول order_items
          $order_item_query = "INSERT INTO order_items (OrderID, ProdID, Quantity, Price) VALUES ('$order_id', '$product_id', '$quantity', '$price')";
          if (!mysqli_query($con, $order_item_query)) {
            // في حال حدوث خطأ أثناء إدخال عنصر من عناصر الطلب
            echo "فشل في إدخال عنصر من عناصر الطلب: " . mysqli_error($con);
          }
        } else {
          echo "المنتج ذو المعرف $product_id غير موجود.";
        }
      }

      foreach ($_SESSION['cart'] as $key => $item) {
        unset($_SESSION['cart'][$key]);
      }
      echo '<div class="masseg"><h4>تم تأكيد الطلب</h4></div>';
      header('location: index.php');
    } else {
      echo "فشل في تأكيد الطلب: " . mysqli_error($con);
    }
  } else {
    echo '<div class="masseg"><h4>سلة المشتريات فارغة.</h4></div>';
  }
}


// dashboard 

if (isset($_POST['cat'])) {
  // Escape the category name to prevent SQL injection
  $name = mysqli_real_escape_string($con, $_POST['name']);

  // Process the uploaded image
  $img_tmp_name = $_FILES['img']['tmp_name'];
  $img_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
  $allowed_extensions = ['png', 'jpeg', 'jpg'];

  if (!in_array($img_extension, $allowed_extensions)) {
    die('امتداد الملف غير مسموح.');
  }

  // Update the image counter
  $counterFile = 'counter.txt';
  $currentCounter = 0;

  if (file_exists($counterFile)) {
    $currentCounter = file_get_contents($counterFile);
    if ($currentCounter === false) {
      die('خطأ في قراءة ملف العداد.');
    }
  }

  $currentCounter = intval($currentCounter);
  $newCounter = $currentCounter + 1;

  if (file_put_contents($counterFile, $newCounter) === false) {
    die('خطأ في كتابة ملف العداد.');
  }

  // Construct the new image name
  $new_img_name = "فئة_زركش" . $newCounter . "." . $img_extension;
  $catimg = "Assets/imgs/" . $new_img_name;

  // Move the uploaded file to the desired path
  if (!move_uploaded_file($img_tmp_name, $catimg)) {
    die('فشل في رفع الملف.');
  }

  // Insert the data into the database
  $sql = "INSERT INTO categories (name, Catimg) VALUES ('$name', '$catimg')";

  if (!mysqli_query($con, $sql)) {
    die('خطأ في إدخال البيانات في قاعدة البيانات: ' . mysqli_error($con));
  }

  echo 'نجح الرفع';
}

// uplode new product

if (isset($_POST['prod'])) {
  // الاتصال بقاعدة البيانات

  $name = mysqli_real_escape_string($con, $_POST['name']);
  $purchase_price = mysqli_real_escape_string($con, $_POST['purchase_price']);
  $price = $purchase_price + ($purchase_price * 25 / 100) + 5 + 20;
  $description = mysqli_real_escape_string($con, $_POST['description']);
  $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
  $supplier_id = mysqli_real_escape_string($con, $_POST['supplier_id']);
  $category_id = mysqli_real_escape_string($con, $_POST['category']);

  // معالجة الصورة المرفوعة
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

  $sql = "INSERT INTO products(name, price, purchase_price, description, quantity, supplier_id, img, category_id) 
            VALUES ('$name', '$price', '$purchase_price', '$description', '$quantity', '$supplier_id', '$prodimg', '$category_id')";

  if (mysqli_query($con, $sql)) {
    echo 'نجح الرفع';
  } else {
    echo 'فشل الرفع: ' . mysqli_error($con);
  }
}

// hijry date 
function gregorianToHijri($year, $month, $day)
{
  $jd = gregoriantojd($month, $day, $year);
  $l = $jd - 1948440 + 10632;
  $n = (int)(($l - 1) / 10631);
  $l = $l - 10631 * $n + 354;
  $j = ((int)((10985 - $l) / 5316)) * ((int)((50 * $l) / 17719)) + ((int)($l / 5670)) * ((int)((43 * $l) / 15238));
  $l = $l - ((int)((30 - $j) / 15)) * ((int)((17719 * $j) / 50)) - ((int)($j / 16)) * ((int)((15238 * $j) / 43)) + 29;
  $month = (int)((24 * $l) / 709);
  $day = $l - (int)((709 * $month) / 24);
  $year = 30 * $n + $j - 30;

  return array($year, $month, $day);
}

// الحصول على التاريخ الميلادي الحالي
$gregorianDate = date("Y-m-d");
list($year, $month, $day) = explode('-', $gregorianDate);

// تحويل التاريخ الميلادي إلى التاريخ الهجري
$hijriDate = gregorianToHijri($year, $month, $day);
