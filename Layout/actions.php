<?php
session_start();
// conection
$con = mysqli_connect('localhost', 'root', '', 'jasmineboutique');
if (!$con) {
  die('error' . mysqli_connect_error());
}
if (isset($_SESSION['id'])) {
  $UserID = $_SESSION['id'];
  $info = mysqli_query($con, "SELECT * FROM users WHERE id='$UserID'");
  $data = mysqli_fetch_array($info);

  $CartQuery = "SELECT * FROM products INNER JOIN cart ON products.id = cart.ProductID WHERE cart.UserID = '$UserID'";
  $CartResult = mysqli_query($con, $CartQuery);
  $SaveQuery = "SELECT * FROM products INNER JOIN Saves ON products.id = Saves.ProductID WHERE Saves.UserID = '$UserID'";
  $SaveResult = mysqli_query($con, $SaveQuery);

  $products = mysqli_query($con, "SELECT * FROM products");
  if (isset($_GET['id'])) {
    $ProductID = mysqli_real_escape_string($con, $_GET['id']);
    $check_sql_like = "SELECT * FROM Saves WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
    $check_result_like = mysqli_query($con, $check_sql_like);
  }
}
// Saves form 

if (isset($_POST['like'])) {
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
}

// Cart form add
if (isset($_POST['addCart'])) {
  if (isset($_POST['Quantity'])) {
    $Quantity = mysqli_real_escape_string($con, $_POST['Quantity']);
  } else {
    $Quantity = 1;
  }
  if (isset($_GET['id'])) {
    $ProductID = mysqli_real_escape_string($con, $_GET['id']);
    $check_sql = "SELECT * FROM products WHERE id = '$ProductID'";
    $check_result = mysqli_query($con, $check_sql);
    $row = mysqli_fetch_assoc($check_result);
    $price = $row['price'];

    $check_sql = "SELECT * FROM cart WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
    $check_result = mysqli_query($con, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
      $row = mysqli_fetch_assoc($check_result);
      $existing_Quantity = $row['Quantity'];
      $new_Quantity = $existing_Quantity + $Quantity;
      $update_sql = "UPDATE cart SET Quantity = $new_Quantity WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
      mysqli_query($con, $update_sql);
      // --------------
      $up_sql = "UPDATE orders SET Quantity = $new_Quantity  WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
      mysqli_query($con, $up_sql);
      // --------------
      $check_sql = "SELECT * FROM products WHERE id = '$ProductID'";
      $check_result = mysqli_query($con, $check_sql);
      $row = mysqli_fetch_assoc($check_result);
      $existing_price = $row['price'];
      $new_price = $existing_price * $new_Quantity;
      $update_sql = "UPDATE orders SET price = $new_price WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
      mysqli_query($con, $update_sql);
      // --------------
      $check_sql = "SELECT * FROM products WHERE id = '$ProductID'";
      $check_result = mysqli_query($con, $check_sql);
      $row = mysqli_fetch_assoc($check_result);
      $existing_sale = $row['salse'];
      $new_sale = $existing_sale + $new_Quantity;
      $sql = "UPDATE `products` SET `salse` = '$new_sale' WHERE `products`.`id` = '$ProductID'";
      mysqli_query($con, $sql);
    } else {
      $sql = "INSERT INTO `cart`(UserID, ProductID, Quantity) 
            VALUES ('$UserID', '$ProductID', $Quantity)";

      mysqli_query($con, $sql);
      $sqll = "INSERT INTO `orders`(UserID, ProductID	, Quantity ,price ,OrderStatus) 
            VALUES ('$UserID', '$ProductID', '$Quantity' ,'$price' ,1)";
      mysqli_query($con, $sqll);
    }
  }
}
// Cart form delete
if (isset($_POST['delCart'])) {
  $check_sql = "SELECT * FROM cart WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
  $check_result = mysqli_query($con, $check_sql);
  $row = mysqli_fetch_assoc($check_result);
  $cartid = $row['CartID'];
  $update_sql = "DELETE FROM `cart` WHERE `cart`.`cartid` = '$cartid'";
  mysqli_query($con, $update_sql);
  // --------------
  $chec_sql = "SELECT * FROM orders WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
  $chec_result = mysqli_query($con, $chec_sql);
  $row = mysqli_fetch_assoc($chec_result);
  $OrderID = $row['OrderID'];
  $up_sql = "DELETE FROM `orders` WHERE `orders`.`OrderID` = '$OrderID'";
  mysqli_query($con, $up_sql);
  // --------------
  $new_Quantity = $row['Quantity'];
  $check_sql = "SELECT * FROM products WHERE id = '$ProductID'";
  $check_result = mysqli_query($con, $check_sql);
  $row = mysqli_fetch_assoc($check_result);
  $existing_sale = $row['salse'];
  $new_sale = $existing_sale - $new_Quantity;
  $sql = "UPDATE `products` SET `salse` = '$new_sale' WHERE `products`.`id` = '$ProductID'";
  mysqli_query($con, $sql);
}
// Cart form update quante
if (isset($_POST['upQuantity'])) {
  $Quantity = mysqli_real_escape_string($con, $_POST['Quantity']);
  if (isset($_GET['id'])) {
    $ProductID = mysqli_real_escape_string($con, $_GET['id']);
    $update_sql = "UPDATE cart SET Quantity = $Quantity WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
    mysqli_query($con, $update_sql);
    $update_sqll = "UPDATE orders SET Quantity = $Quantity WHERE UserID = '$UserID' AND ProductID = '$ProductID'";
    mysqli_query($con, $update_sqll);
    // --------
    $check_sql = "SELECT * FROM products WHERE id = '$ProductID'";
    $check_result = mysqli_query($con, $check_sql);
    $row = mysqli_fetch_assoc($check_result);
    $existing_sale = $row['salse'];
    $new_sale = $Quantity + $existing_sale;
    $sql = "UPDATE `products` SET `salse` = '$new_sale' WHERE `products`.`id` = '$ProductID'";
    mysqli_query($con, $sql);
  }
}
// Send Order
if (isset($_POST['sndord'])) {
  // إضافة بيانات الطلب إلى جدول الطلبات
  $sql = "INSERT INTO `order` (UserID, OrderStatus) 
            VALUES ('$UserID', 1)";
  mysqli_query($con, $sql);
  // يفضل استخدام استعلام معدل مع تحقق من عملية الاستعلام

  // استعلام لاسترداد بيانات السلة
  $check_sql = "SELECT * FROM cart WHERE UserID = '$UserID'";
  $check_result = mysqli_query($con, $check_sql);

  // حذف بيانات السلة
  if (mysqli_num_rows($check_result) > 0) {
    $row = mysqli_fetch_assoc($check_result);
    $cartid = $row['CartID'];
    $update_sql = "DELETE FROM cart WHERE UserID = '$UserID'";
    mysqli_query($con, $update_sql); // يفضل استخدام استعلام معدل مع تحقق من عملية الاستعلام
  }

  exit(); // تأكد من وقف تنفيذ النص بعد التوجيه
}

// dashboard 

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
  $imglink = mysqli_real_escape_string($con, $_POST['img']);
  $supplier_id = mysqli_real_escape_string($con, $_POST['supplier_id']);
  $category_id = mysqli_real_escape_string($con, $_POST['category']);

  $counterFile = 'counter.txt';
  $currentCounter = file_exists($counterFile) ? file_get_contents($counterFile) : 0;
  $currentCounter = intval($currentCounter);
  $newCounter = $currentCounter + 1;
  file_put_contents($counterFile, $newCounter);
  $img_tmp_name = $_FILES['img']['tmp_name'];
  $img_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION); // الحصول على الامتداد
  $new_img_name = "منتج_زركش" . $newCounter . "." . $img_extension;
  $prodimg = "Assets/imgs/" . $new_img_name;

  $err_s = 0;

  if ($err_s == 0) {
    $sql = "INSERT INTO products(name, price, purchase_price, description, quantity, supplier_id, img, category_id) 
    VALUES ('$name', '$price', '$purchase_price', '$description', '$quantity', '$supplier_id', '$prodimg', '$category_id')";

    mysqli_query($con, $sql);
    echo 'نجح الرفع';
  } else {
    echo 'فشل الرفع';
  }
}


// hijry date 
function gregorianToHijri($year, $month, $day) {
    $jd = gregoriantojd($month, $day, $year);
    $l = $jd - 1948440 + 10632;
    $n = (int)(( $l - 1 ) / 10631);
    $l = $l - 10631 * $n + 354;
    $j = ((int)(( 10985 - $l ) / 5316)) * ((int)(( 50 * $l ) / 17719)) + ((int)( $l / 5670)) * ((int)(( 43 * $l ) / 15238));
    $l = $l - ((int)(( 30 - $j ) / 15)) * ((int)(( 17719 * $j ) / 50)) - ((int)( $j / 16)) * ((int)(( 15238 * $j ) / 43)) + 29;
    $month = (int)(( 24 * $l ) / 709);
    $day = $l - (int)(( 709 * $month ) / 24);
    $year = 30 * $n + $j - 30;

    return array($year, $month, $day);
}

// الحصول على التاريخ الميلادي الحالي
$gregorianDate = date("Y-m-d");
list($year, $month, $day) = explode('-', $gregorianDate);

// تحويل التاريخ الميلادي إلى التاريخ الهجري
$hijriDate = gregorianToHijri($year, $month, $day);


