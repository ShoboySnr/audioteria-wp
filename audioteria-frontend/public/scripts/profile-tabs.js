const menuButton = document.querySelector('.account-menu');
console.log(menuButton);
let isOpened = false;
menuButton.addEventListener('click', () => {
  if (isOpened) {
    document.querySelector('.account-links-wrapper').style.display = 'none';
    document.querySelector('.account-menu .open').style.display = 'block';
    document.querySelector('.account-menu .close').style.display = 'none';
    menuButton.style.top = '-68px';
    isOpened = !isOpened;
  } else {
    document.querySelector('.account-links-wrapper').style.display = 'block';
    document.querySelector('.account-menu .open').style.display = 'none';
    document.querySelector('.account-menu .close').style.display = 'block';
    menuButton.style.top = '-75px';
    isOpened = !isOpened;
  }
});
