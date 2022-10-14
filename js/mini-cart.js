const addClassFuntion = () => {
  document.getElementById( 'audioteria-mini-cart-dropdown').classList.add('cart-open');
}

const removeClassFuntion = () => {
  document.getElementById( 'audioteria-mini-cart-dropdown').classList.remove('cart-open');
}

const toggleClassFuntion = () => {
  document.getElementById( 'audioteria-mini-cart-dropdown').classList.toggle('cart-open');
}

window.addEventListener("load", () => {
  document.getElementById( 'mini-cart-dropdown' ).addEventListener('click', toggleClassFuntion );

  document.getElementById( 'close-mini-cart' ).addEventListener('click',removeClassFuntion );
});

document.addEventListener( 'click', function(e) {
  const isClickInside = document.getElementById( 'audioteria-mini-cart-dropdown').contains( e.target );

  const isClickIcon = document.getElementById( 'mini-cart-dropdown').contains( e.target );

  const cartIsOpen = document.getElementById( 'audioteria-mini-cart-dropdown').classList.contains('cart-open');

  if ( ! isClickInside && ! isClickIcon && cartIsOpen ) {
    removeClassFuntion();
  }
} );