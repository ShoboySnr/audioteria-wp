const tabButtons = document.querySelectorAll('.about-tab-buttons button');
const tabContents = document.querySelectorAll('.about-tab-content');

tabButtons.forEach((tabButton, index) => {
  tabButton.addEventListener('click', () => {
    tabContents.forEach((tab, index) => {
      tab.style.display = 'none';
      tabButtons[index].classList.remove('active');
    });
    tabButton.classList.add('active');
    tabContents[index].style.display = 'block';
  });
});
