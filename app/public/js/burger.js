document.addEventListener('DOMContentLoaded', function () {
  const burgerBtn = document.querySelector('.burger');
  const menu = document.querySelector('.open');

  if (burgerBtn && menu) {
    burgerBtn.addEventListener('click', function () {
      menu.classList.toggle('active');
    });
  }
});
