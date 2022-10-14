window.addEventListener('DOMContentLoaded', () => {
  rateListener();
});

const rateListener = () => {
  const rateModal = document.querySelector('#trailer-modal');
  const rateProductButton = document.querySelector('#rate-toggle');
  const nextSib =  rateProductButton.nextElementSibling;
  const closeModalButton = rateModal.querySelector(' #close');

  const openModal = () => {
    rateModal.removeAttribute('class');
    rateModal.classList.add('four');
    document.querySelector('body').style.overflow = 'hidden';
  };
  const closeModal = () => {
    rateModal.classList.remove('four');
    document.querySelector('body').style.overflow = 'auto';
  };

  window.addEventListener('click', (event) => {

    if (event.target === document.querySelector('.trailer-modal-background')) {
      closeModal();
    }
  });
  rateProductButton.addEventListener('click', openModal);
  closeModalButton.addEventListener('click', closeModal);
};
