window.addEventListener('DOMContentLoaded', () => {
  premierSlider();
  categorySlider('sci-Fi');
  categorySlider('drama');
  categorySlider('horror');
  window.addEventListener('resize', () => {
    premierSlider();
    categorySlider('sci-Fi');
    categorySlider('drama');
    categorySlider('horror');
  });
});

const carousel = () => {
  const pointers = document.querySelectorAll('.home-heading-pointer span');
  pointers[0].setAttribute('style', 'background-color: #CB5715');
  const headings = document.querySelectorAll('.home-heading');
  const first = () => {
    pointers[0].setAttribute('style', 'background-color: #FFFFFF');
    pointers[1].setAttribute('style', 'background-color: #CB5715');
    pointers[2].setAttribute('style', 'background-color: #FFFFFF');
    headings.forEach((heading, index) => {
      if (index > 1) {
        heading.style.transition = 'none';
      }
      else {
        heading.style.transition = 'all .3s';
      }
      heading.style.transform = 'translateX(-100%)';
    });
  };
  const second = () => {
    pointers[0].setAttribute('style', 'background-color: #FFFFFF');
    pointers[1].setAttribute('style', 'background-color: #FFFFFF');
    pointers[2].setAttribute('style', 'background-color: #CB5715');

    headings[2].style.transition = 'all .3s';
    headings[0].style.transform = 'translateX(100%)';
    headings[1].style.transform = 'translateX(-200%)';
    headings[2].style.transform = 'translateX(-200%)';
  };
  const third = () => {
    pointers[0].setAttribute('style', 'background-color: #CB5715');
    pointers[1].setAttribute('style', 'background-color: #FFFFFF');
    pointers[2].setAttribute('style', 'background-color: #FFFFFF');
    headings[0].style.transition = 'all .3s';
    headings[1].style.transition = 'none';
    // headings[2].style.transition = 'none';
    headings[0].style.transform = 'translateX(0%)';
    headings[1].style.transform = 'translateX(0%)';
    headings[2].style.transform = 'translateX(-300%)';
  };
  setTimeout(first, 3000);
  setTimeout(second, 6000);
  setTimeout(() => {
    third();
    carousel();
  }, 9000);
};
const premierSlider = () => {
  const leftArrow = document.querySelector('.premier-releases .left-arrow');
  const rightArrow = document.querySelector('.premier-releases .right-arrow');
  const wrapper = document.querySelector('.premier-releases-wrapper');
  const item = document.querySelectorAll('.premier-releases-wrapper-item');

  let translate = 0;
  let noOfCardsOnScreen = 3;
  if (window.innerWidth < 640) {
    noOfCardsOnScreen = 1;
  }

  // Set listeners and attributes to defaults
  leftArrow.addEventListener('click', null);
  rightArrow.addEventListener('click', null);
  wrapper.setAttribute('style', `transform: translateX(0%)`);
  rightArrow.setAttribute('style', 'opacity: 1');
  leftArrow.setAttribute('style', 'opacity: 0.5');
  const parentContainer = document.querySelector('.premier-releases-container');

  leftArrow.addEventListener('click', () => {
    if (translate > 0) {
      translate -= 100;
      wrapper.setAttribute('style', `transform: translateX(-${translate}%)`);
      rightArrow.setAttribute('style', 'opacity: 1');
      parentContainer.classList.add('premier-releases-container-after');
      if (translate <= 0) {
        leftArrow.setAttribute('style', 'opacity: 0.5');
        parentContainer.classList.remove('premier-releases-container-before');
      }
    }
  });
  rightArrow.addEventListener('click', () => {
    if (noOfCardsOnScreen === 1) {
      if (translate < (item.length / noOfCardsOnScreen - 1) * 100) {
        parentContainer.classList.add('premier-releases-container-before');
        translate += 100;
        wrapper.setAttribute('style', `transform: translateX(-${translate}%)`);
        leftArrow.setAttribute('style', 'opacity: 1');
        if (translate > (item.length / noOfCardsOnScreen - 1) * 100) {
          rightArrow.setAttribute('style', 'opacity: 0.5');
          parentContainer.classList.remove('premier-releases-container-after');
        }
      }
    } else if (translate <= (item.length / noOfCardsOnScreen - 1) * 100) {
      parentContainer.classList.add('premier-releases-container-before');
      translate += 100;
      wrapper.setAttribute('style', `transform: translateX(-${translate}%)`);
      leftArrow.setAttribute('style', 'opacity: 1');
      if (translate > (item.length / noOfCardsOnScreen - 1) * 100) {
        rightArrow.setAttribute('style', 'opacity: 0.5');
        parentContainer.classList.remove('premier-releases-container-after');
      }
    }
  });
};

const categorySlider = (category) => {
  let noOfCardsOnScreen = 5;
  if (window.innerWidth < 640) {
    noOfCardsOnScreen = 1;
  } else if (window.innerWidth < 1024) {
    noOfCardsOnScreen = 3;
  } else if (window.innerWidth < 1280) {
    noOfCardsOnScreen = 5;
  }
  const parentContainer = document.querySelector(
    `.${category} .category-container`
  );
  const leftArrow = document.querySelector(`.${category} .left-arrow`);
  const rightArrow = document.querySelector(`.${category} .right-arrow`);
  const wrapper = document.querySelector(`.${category} .category-wrapper`);
  const bookCards = document.querySelectorAll(
    `.${category} .category-wrapper-item`
  );
  let translate = 0;

  // Set listeners and attributes to defaults
  leftArrow.addEventListener('click', null);
  rightArrow.addEventListener('click', null);
  wrapper.setAttribute('style', `transform: translateX(0%)`);
  rightArrow.setAttribute('style', 'opacity: 1');
  leftArrow.setAttribute('style', 'opacity: 0.5');

  leftArrow.addEventListener('click', () => {
    if (translate > 0) {
      translate -= 100;
      wrapper.setAttribute('style', `transform: translateX(-${translate}%)`);
      rightArrow.setAttribute('style', 'opacity: 1');
      parentContainer.classList.add('category-container-after');
      if (translate <= 0) {
        leftArrow.setAttribute('style', 'opacity: 0.5');
        parentContainer.classList.remove('category-container-before');
      }
    }
  });
  rightArrow.addEventListener('click', () => {
    if (noOfCardsOnScreen === 1) {
      if (translate < (bookCards.length / noOfCardsOnScreen - 1) * 100) {
        translate += 100;
        parentContainer.classList.add('category-container-before');
        wrapper.setAttribute('style', `transform: translateX(-${translate}%)`);
        leftArrow.setAttribute('style', 'opacity: 1');
        if (translate > (bookCards.length / noOfCardsOnScreen - 1) * 100) {
          rightArrow.setAttribute('style', 'opacity: 0.5');
          parentContainer.classList.remove('category-container-after');
        }
      }
    } else if (translate <= (bookCards.length / noOfCardsOnScreen - 1) * 100) {
      parentContainer.classList.add('category-container-before');
      translate += 100;
      wrapper.setAttribute('style', `transform: translateX(-${translate}%)`);
      leftArrow.setAttribute('style', 'opacity: 1');
      if (translate > (bookCards.length / noOfCardsOnScreen - 1) * 100) {
        rightArrow.setAttribute('style', 'opacity: 0.5');
        parentContainer.classList.remove('category-container-after');
      }
    }
  });
};
