const handleMobileSearch = () => {
  const search = document.querySelector('.nav-search');
  const searchInput = document.querySelector('.nav-search input');
  const mobileLogo = document.querySelector('.nav-main-logo-mobile');
  search.addEventListener('click', null);
  window.addEventListener('click', null);
  if (window.innerWidth < 640) {
    search.addEventListener('click', () => {
      searchInput.style.display = 'block';
      mobileLogo.style.display = 'none';
    });
    window.addEventListener('click', (e) => {
      if (!e.target.className.includes('nav-search') || !e.target.className.includes('nav-search-input')) {
        searchInput.style.display = 'none';
        mobileLogo.style.display = 'block';
      }
    });
  }
};

window.addEventListener('resize', () => {
  handleMobileSearch();
});
