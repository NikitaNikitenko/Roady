let lastScrollTop = 0;

window.addEventListener("scroll", function() {
  let st = window.pageYOffset || document.documentElement.scrollTop;
  if (st > lastScrollTop) {
    document.querySelector(".footer").classList.add("hidden");
  } else {
    document.querySelector(".footer").classList.remove("hidden");
  }
  lastScrollTop = st <= 0 ? 0 : st;
}, false);
