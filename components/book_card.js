class BookCard extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    const imageSrc= this.getAttribute("image-src");
    const title = this.getAttribute("title");
    const price = this.getAttribute("price");
    const content = this.getAttribute("content");
	const product_link = this.getAttribute("product_link");
    this.innerHTML = `
		<article class="book-card">
			<img class="book-card-image" src="${imageSrc}" alt="${title}" />
			<article class="book-card-content">
				<h3 class="title">${title}</h3>
				<p class="content">${content}</p>

				<div class="price-buy-section">
					<span class="price">${price}</span>
					<a href="${product_link}">
					<button>
					Buy
					</button>
					</a>
				</div>
			</article>
		</article>   `;
  }
}
window.customElements.define("book-card", BookCard);
