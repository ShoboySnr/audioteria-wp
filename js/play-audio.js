function Productjs() {};

Productjs.playAudio = () => {
  const audioFile = document.getElementById("trailer_audio");
  const playTrailerBtn = document.querySelector('#audio-player');
  var playing = false;
  if (playTrailerBtn !== null) {
      playTrailerBtn.addEventListener('click', () => {
          if (playing == false) {
              audioFile.play();
              playing = true;
              playTrailerBtn.innerHTML = '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="50.6171" cy="50.6172" r="43.2099" fill="black" /> <path d="M50 6.25C25.8398 6.25 6.25 25.8398 6.25 50C6.25 74.1602 25.8398 93.75 50 93.75C74.1602 93.75 93.75 74.1602 93.75 50C93.75 25.8398 74.1602 6.25 50 6.25ZM64.0723 50.6738L42.7441 66.1914C42.6272 66.2754 42.4894 66.3256 42.3458 66.3364C42.2022 66.3472 42.0584 66.3182 41.9303 66.2526C41.8021 66.187 41.6945 66.0873 41.6193 65.9645C41.5441 65.8418 41.5041 65.7006 41.5039 65.5566V34.541C41.5034 34.3967 41.5429 34.2552 41.618 34.132C41.6931 34.0087 41.8008 33.9087 41.9292 33.843C42.0576 33.7773 42.2018 33.7484 42.3456 33.7595C42.4894 33.7707 42.6274 33.8215 42.7441 33.9063L64.0723 49.4141C64.173 49.4853 64.2552 49.5797 64.312 49.6893C64.3687 49.7989 64.3983 49.9205 64.3983 50.0439C64.3983 50.1674 64.3687 50.289 64.312 50.3986C64.2552 50.5082 64.173 50.6026 64.0723 50.6738Z" fill="red" /> </svg>';
          } else {
              audioFile.pause();
              playing = false;
              playTrailerBtn.innerHTML = '<svg width="88" height="88" viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="43.75" cy="43.75" r="43.75" fill="white"/><path d="M31.75 29.4H38.8V57.6H31.75V29.4ZM48.2 29.4H55.25V57.6H48.2V29.4Z" fill="black"/></svg>';
          }
      });
  }
};

Productjs.toggleSharebox = () => {
    const shareToggle = document.getElementById('share-toggle');
    const shareBox = document.getElementById('share-box');
    const closebox = document.getElementById('close-share');

    shareToggle.addEventListener('click', function () {
      shareBox.classList.toggle("box-open");
    });
    closebox.addEventListener('click', function () {
      shareBox.classList.toggle("box-open");
    });
}

window.addEventListener('DOMContentLoaded', () => {
  Productjs.playAudio();
  Productjs.toggleSharebox();
});
