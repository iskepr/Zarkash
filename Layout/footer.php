<footer>
  <ul>
    <a href="https://www.facebook.com/szarkash/"><i class="fa-brands fa-facebook"></i></a>
    <a href="https://www.instagram.com/szarkash/"><i class="fa-brands fa-instagram"></i></a>
  </ul>
  <hr>
  <p>© <?php echo $hijriDate[0]; ?>-<?php echo date("Y"); ?>, زركش | <a href="https://skepr.rf.gd/">skepr يعمل بواسطة </a></p>
</footer>
<script src="js/script.js"></script>
<script>
  function updateCart(productId, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        location.reload();
      }
    };
    xhr.send("updateCart=1&ProductID=" + productId + "&Quantity=" + quantity);
  }

  function deleteFromCart(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        location.reload();
      }
    };
    xhr.send("delCart=1&ProductID=" + productId);
  }
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</body>

</html>
