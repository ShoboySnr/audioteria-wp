window.addEventListener('DOMContentLoaded', () => {
  bookTabListener();
});

const bookTabListener = () => {
  document
    .querySelectorAll('.content-section-tab button')
    .forEach((item, index) => {
      item.addEventListener('click', (e) => {
        handleSwitch(e, index);
      });
    });
};
const contents = document.querySelectorAll('.content');
const handleSwitch = (e, index) => {
  e.preventDefault();

  document
    .querySelector('.content-section-tab button.active')
    .classList.remove('active');
  e.srcElement.classList.toggle('active');
  document.querySelector('.content.active').classList.remove('active');
  document.querySelectorAll('.content')[index].classList.add('active');
};
