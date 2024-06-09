document.addEventListener("DOMContentLoaded", function () {
  const hamburgerCheckbox = document.getElementById("hamburger");
  const navUl = document.querySelector("header ul");

  hamburgerCheckbox.addEventListener("change", function () {
    if (hamburgerCheckbox.checked) {
      navUl.classList.add("open");
    } else {
      navUl.classList.remove("open");
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  var swipers = document.querySelectorAll(".swiper-container");
  swipers.forEach(function (swiperContainer) {
    new Swiper(swiperContainer, {
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      slidesPerView: "auto", // عرض جميع الشرائح معًا
      spaceBetween: 10, // مساحة بين المنتجات
    });
  });
});

// search function
function searchmodel() {
  const searchModel = document.getElementById("searchModel");
  // يتم تبديل خاصية العرض بين block و none
  if (searchModel.style.display === "block") {
    searchModel.style.display = "none";
  } else {
    searchModel.style.display = "block";
  }
}

document.getElementById("searchQuery").addEventListener("input", function () {
  var query = this.value;
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "s.php?query=" + encodeURIComponent(query), true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        document.getElementById("results").innerHTML = xhr.responseText;
      } else {
        console.error("حدث خطأ: " + xhr.status);
      }
    }
  };
  xhr.send();
});

// cart function
document.addEventListener("DOMContentLoaded", (event) => {
  // عند تحميل الصفحة، تحقق من حالة النافذة في localStorage
  const cartmodel = document.getElementById("cartmodel");
  const isCartModelOpen = localStorage.getItem("cartmodelState") === "open";

  if (isCartModelOpen) {
    cartmodel.style.display = "block";
  } else {
    cartmodel.style.display = "none";
  }
});

function cartmodel() {
  const cartmodel = document.getElementById("cartmodel");

  if (cartmodel.style.opacity === "1") {
    cartmodel.style.opacity = "0";
    cartmodel.style.display = "none";
    localStorage.setItem("cartmodelState", "closed"); // احفظ الحالة كـ "مغلق"
  } else {
    cartmodel.style.opacity = "1";
    cartmodel.style.display = "block";
    localStorage.setItem("cartmodelState", "open"); // احفظ الحالة كـ "مفتوح"
  }
}

// like function
function likeModel() {
  const likeModel = document.getElementById("likeModel");
  // يتم تبديل خاصية العرض بين block و none
  if (likeModel.style.display === "block") {
    likeModel.style.display = "none";
  } else {
    likeModel.style.display = "block";
  }
}

// cut photo
document.getElementById('imgInput').addEventListener('change', function (e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('imgPreview').src = e.target.result;
      document.getElementById('cropContainer').style.display = 'block';
      const cropper = new Cropper(document.getElementById('imgPreview'), {
        aspectRatio: 1,
        viewMode: 1,
      });
      document.getElementById('cropButton').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas();
        canvas.toBlob(function (blob) {
          const formData = new FormData();
          formData.append('croppedImg', blob, 'cropped.jpg');
          const reader = new FileReader();
          reader.onloadend = function () {
            document.getElementById('croppedImg').value = reader.result;
            document.getElementById('uploadForm').submit();
          };
          reader.readAsDataURL(blob);
        });
      });
    };
    reader.readAsDataURL(file);
  }
});
