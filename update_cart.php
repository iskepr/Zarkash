<?php
session_start();

if (isset($_POST['updateCart'])) {
  $ProductID = $_POST['ProductID'];
  $Quantity = $_POST['Quantity'];

  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $ProductID) {
      $item['quantity'] = $Quantity;
      break;
    }
  }
  echo "success";
  exit();
}

if (isset($_POST['delCart'])) {
  $ProductID = $_POST['ProductID'];

  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $ProductID) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }
  $_SESSION['cart'] = array_values($_SESSION['cart']);
  echo "success";
  exit();
}
