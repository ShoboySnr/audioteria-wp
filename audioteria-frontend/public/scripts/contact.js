const faqButtons = document.querySelectorAll('.faq-button');
const faqAnswers = document.querySelectorAll('.faq-answer');

const faqOpen = [];
faqButtons.forEach((button, index) => {
  faqOpen.push(false);
  button.addEventListener('click', () => {
    if (faqOpen[index]) {
      faqAnswers[index].style.display = 'none';
      button.classList.remove('active-btn');
      faqOpen[index] = false;
    } else {
      faqAnswers[index].style.display = 'block';
      button.classList.add('active-btn');
      faqOpen[index] = true;
    }
  });
});

const viewMoreBtn = document.querySelector('.faq-view-more');
const faqs = document.querySelectorAll('.faq-wrapper');

if (faqs.length < 4) {
  viewMoreBtn.style.display = 'none';
}
faqs.forEach((faq, index) => {
  if (index > 3) {
    faq.style.display = 'none';
  }
});

let showMore = true;
viewMoreBtn.addEventListener('click', () => {
  if (showMore) {
    faqs.forEach((faq, index) => {
      if (index > 3) {
        faq.style.display = 'block';
      }
    });
    viewMoreBtn.innerHTML =
      'View less <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8.5L7 3.5L2 8.5L0 7.5L7 0.5L14 7.5L12 8.5Z" fill="#CB5715"/></svg>';
    showMore = !showMore;
  } else {
    faqs.forEach((faq, index) => {
      if (index > 3) {
        faq.style.display = 'none';
      }
    });
    viewMoreBtn.innerHTML =
      'View more <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M2 0.5L7 5.5L12 0.5L14 1.5L7 8.5L0 1.5L2 0.5Z" fill="#CB5715" /></svg>';
    showMore = !showMore;
  }
});
