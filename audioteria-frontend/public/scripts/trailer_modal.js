window.addEventListener('DOMContentLoaded', () => {
  playListener();
});

const playListener = () => {
  const trailerModal = document.querySelector('#trailer-modal');
  const playTrailerButton = document.querySelector('#play-trailer');
  const closeModalButton = trailerModal.querySelector(' #close');

  const openModal = () => {
    trailerModal.removeAttribute('class');
    trailerModal.classList.add('four');
    document.querySelector('body').style.overflow = 'hidden';
  };
  const closeModal = () => {
    trailerModal.classList.remove('four');
    document.querySelector('body').style.overflow = 'auto';
  };

  window.addEventListener('click', (event) => {
    if (event.target === document.querySelector('.trailer-modal-background')) {
      closeModal();
    }
  });
  playTrailerButton.addEventListener('click', openModal);
  closeModalButton.addEventListener('click', closeModal);
};
