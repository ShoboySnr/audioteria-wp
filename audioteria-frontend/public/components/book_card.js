class BookCard extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    const imageSrc = this.getAttribute('image-src');
    const title = this.getAttribute('title');
    const price = this.getAttribute('price');
    const content = this.getAttribute('content');
    this.innerHTML = `
		<article class="book-card">
			<a href="product.html" title="${title}">
				<img class="book-card-image" src="${imageSrc}" alt="${title}" />
			</a>
			<article class="book-card-content">
				<h3 class="title">${title}</h3>
				<p class="content">${content}</p>

				<div class="price-buy-section">
					<span class="price">${price}</span>
					<button onclick="window.location.href='./shopping-basket.html'" >
						Buy
					</button>
				</div>
			</article>
		</article>   `;
  }
}
window.customElements.define('book-card', BookCard);
