<?php
session_start();
// conection
$con = mysqli_connect('localhost', 'root', '', 'zarkash');
if (!$con) {
  die('error' . mysqli_connect_error());
}

if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  // Remove unnecessary isset check for $err_s
  // if (!isset($err_s)) {
  $sql = "SELECT id, UserName, phone, adress, city, home, email, img FROM users WHERE email='$email' AND password='$password'";
  $result = mysqli_query($con, $sql);
  $row = mysqli_fetch_assoc($result);
  if ($row) {
    $_SESSION['id'] = $row['id'];
    header('location: index.php');
    exit();
  } else {
    // Remove echo statement here, as header('location: login.php'); will redirect
    $login_error = '<h1>اسم المستخدم أو كلمة المرور غير صحيحة</h1>';
    header('location: Log.php');
    exit();
  }
  // }
}

if (isset($_POST['creat'])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $phone = mysqli_real_escape_string($con, $_POST['phone']);
  $adress = mysqli_real_escape_string($con, $_POST['adress']);
  $city = mysqli_real_escape_string($con, $_POST['city']);
  $home_number = mysqli_real_escape_string($con, $_POST['home_number']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $img = 1;

  $err_s = 0;

  if ($err_s == 0) {
    $sql = "INSERT INTO `users` (`UserName`, `phone`, `adress`, `city`, `home`, `email`, `password`, `img`) 
    VALUES ('$name', '$phone', '$adress', '$city', '$home_number', '$email', '$password', '$img')";
    mysqli_query($con, $sql);

    $sqll = "SELECT id, `name`, phone, adress, city, home, email, `password`, img FROM users WHERE email='$email' AND `password`='$password'";
    $result = mysqli_query($con, $sqll);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
      $_SESSION['id'] = $row['id'];
      header('location: index.php');
      exit();
    } else {
      $login_error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
    }
  }
}

?>
<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="css/vendor.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chilanka&family=Montserrat:wght@300;400;500&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  
<section class="log">
  <div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">

    <div class="login">
      <form method="post" class="form">
        <label for="chk" aria-hidden="true">تسجيل الدخول</label>
        <input placeholder="البريد الالكتروني" name="email" class="input" type="email" required="سلزم ادخال البريد الالكتروني">
        <input placeholder="كلمة السر" name="password" class="input" type="password" required="يجب ادخال كلمة السر">
        <a href="#forg" onclick="forgpass()">نسيت كلمة السر</a>
        <button type="submit" name="login" class="btn">ادخل</button>
      </form>
    </div>

    <div class="register">
      <form method="post" class="form">
        <label for="chk" aria-hidden="true">انشاء حساب</label>
        <input class="input" name="name" type="text" placeholder="الاسم" required maxlength="50" />
        <input class="input" name="phone" type="tel" placeholder="رقم التليفون" required maxlength="14" />
        <div class="adress">
          <input class="input" name="adress" type="adress" placeholder="العنوان" required />
          <select name="city" class="input" id="city">
            <option selected disabled>المحافظة</option>
            <option value="1">القاهرة</option>
            <option value="2">الجيزة</option>
            <option value="3">الاسكندرية</option>
          </select>
          <input class="input" name="home_number" type="number" placeholder="رقم المنزل" required />
        </div>
        <input class="input" name="email" type="email" placeholder="البريد الالكتروني" required />
        <input class="input" name="password" type="password" placeholder="كلمة السر" required maxlength="50" />
        <button type="submit" class="btn" name="creat">انشاء الحساب</button>
      </form>
    </div>
  </div>
</section>

</body>