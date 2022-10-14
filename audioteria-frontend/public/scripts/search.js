const seeAllButton = document.querySelector('.see-all');
const categories = document.querySelectorAll('.search-filter-check');

categories.forEach((category, index) => {
  if (index > 6) {
    category.style.display = 'none';
  }
});

seeAllButton.addEventListener('click', () => {
  categories.forEach((category) => {
    category.style.display = 'block';
  });
  seeAllButton.style.display = 'none';
});

const mobileFilter = document.querySelector('.mobile-filter');
const searchFilter = document.querySelector('.search-filter');

mobileFilter.addEventListener('click', () => {
  searchFilter.style.display = 'block';
});
const filterCloseBtn = document.querySelector('.mobile-filter-heading-button');
filterCloseBtn.addEventListener('click', () => {
  searchFilter.style.display = 'none';
});
