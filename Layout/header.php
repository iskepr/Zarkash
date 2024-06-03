<!DOCTYPE html>
<html lang="ar">

<head>
  <title>زركش</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="Style/style.css" />
  <link rel="stylesheet" href="Style/swiper.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="shortcut icon" href="Assets/Imgs/logo.png" type="image/x-icon">
</head>

<body>
  <div class="background"></div>
  <section class="header">
    <header>
      <div class="ul">
        <a href="profile.php"><span class="material-symbols-outlined">person</span></a>
        <span class="material-symbols-outlined">favorite</span>
        <span class="material-symbols-outlined">shopping_cart</span>
      </div>
      <div class="logo">
        <a href="index.php"><img src="Assets/Imgs/logo.png" alt="زركش"></a>
      </div>
      <div class="search">
        <span class="material-symbols-outlined" onclick="searchmodel()">search</span>
    </header>

    <!-- search model -->
    <div id="searchModel" class="searchmodel">
      <div class="search_container">
        <h1>البحث عن المنتجات</h1>
        <input type="text" name="query" id="searchQuery" placeholder="ادخل اسم المنتج">
        <div id="results"></div>
      </div>
      <script>
        document.getElementById('searchQuery').addEventListener('input', function() {
          var query = this.value;
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 's.php?query=' + encodeURIComponent(query), true);
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
              if (xhr.status === 200) {
                document.getElementById('results').innerHTML = xhr.responseText;
              } else {
                console.error('حدث خطأ: ' + xhr.status);
              }
            }
          };
          xhr.send();
        });
      </script>
    </div>