window.addEventListener('DOMContentLoaded', () => {
  passwordToggle();
});

const passwordToggle = () => {
  let state = true;

  const togglePassword = (e) => {
    e.preventDefault();
    state = !state;
    document
      .querySelector('input#password')
      .setAttribute('type', state ? 'password' : 'text');
  };

  const passwordButton = document.querySelector('#password-button');

  passwordButton.addEventListener('click', togglePassword);
};
