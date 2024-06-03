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

