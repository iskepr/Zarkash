<?php include('layout/header.php');
include('layout/actions.php');
// الحصول على الكلمة المفتاحية من طلب GET
// $query = isset($_GET['query']) ? $_GET['query'] : ''; 
?>


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
<?php include('layout/footer.php'); ?>