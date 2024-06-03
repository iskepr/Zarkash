<?php
include('layout/actions.php');
// الحصول على الكلمة المفتاحية من طلب GET
$query = isset($_GET['query']) ? $_GET['query'] : ''; ?>
    <?php
    if ($query !== '') {
      // إعداد استعلام SQL باستخدام LIKE
      $sql = "SELECT * FROM products WHERE name LIKE ?";
      $stmt = $con->prepare($sql);
      $searchQuery = "%{$query}%";
      $stmt->bind_param("s", $searchQuery);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
    ?>
          <section class="container products">
            <div class="container">
              <div class="products-carousel ">
                <div data-aos="fade-left">
                  <div data-aos="fade-up">
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                      <div class="card">
                        <div class="card_img">
                          <div class="img"><img src="<?php echo $product["img"]; ?>" alt="image"></div>
                        </div>
                        <div class="card_title">
                          <h6><?php echo $product['name']; ?></h6>
                        </div>
                        <div class="card_footer">
                          <?php if (isset($UserID) && $UserID == 0) { ?>
                            <div class="card-footer">
                              <span class="text-title">الكمية <?php echo $product["quantity"] ?></span>
                              <span class="text-title">شراء <?php echo $product["purchase_price"] ?></span>
                              <span class="text-title">بيع <?php echo $product["price"] ?></span>
                              <span class="text-title">ربح <?php echo $product["price"] -  $product["purchase_price"] ?></span>
                              <div class="card_btn"><a href="editprod.php?id=<?php echo $product["id"] ?>">تعديل</a></div>
                            <?php } ?>
                            <div class="card_price">
                              <h3><?php echo $product['price']; ?> جنية</h3>
                            </div>
                            </div>
                        </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </section>
    <?php
        }
      } else {
        echo '<p>لا توجد نتائج للبحث.</p>';
      }

      // إغلاق الاتصال
      $stmt->close();
    }

    $con->close();
    ?>
  </>
</div>
<script>
  window.onload = function() {
    document.getElementById('searchQuery').focus();
  };
</script>