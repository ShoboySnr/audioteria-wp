class BagItem extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    const imageSrc = this.getAttribute('image-src');
    const title = this.getAttribute('title');
    const description = this.getAttribute('description');
    const writer = this.getAttribute('writer');
    const price = this.getAttribute('price');
    const hideRemoveButton = this.getAttribute('hide-remove-button');
    this.innerHTML = `
			<article class="bag-item">
						<img class="image" src="${imageSrc}" alt="${title}">
						<article class="content">
							<div>
								<h3>${title}</h3>
								<span class="price">£${price}</span>
							</div>
							<p class="description">${description}</p>
							<p class="writer">Written By: ${writer}</p>
							<div >
								<span class="price">£${price}</span>
						${
              !hideRemoveButton
                ? `<button class="remove">
                  <svg
                    width="14"
                    height="13"
                    viewBox="0 0 14 13"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.8541 0.646039C12.9007 0.692485 12.9376 0.747661 12.9629 0.808406C12.9881 0.869151 13.001 0.934272 13.001 1.00004C13.001 1.06581 12.9881 1.13093 12.9629 1.19167C12.9376 1.25242 12.9007 1.30759 12.8541 1.35404L1.85414 12.354C1.76026 12.4479 1.63292 12.5007 1.50014 12.5007C1.36737 12.5007 1.24003 12.4479 1.14614 12.354C1.05226 12.2602 0.999512 12.1328 0.999512 12C0.999512 11.8673 1.05226 11.7399 1.14614 11.646L12.1461 0.646039C12.1926 0.599476 12.2478 0.562533 12.3085 0.537327C12.3693 0.51212 12.4344 0.499146 12.5001 0.499146C12.5659 0.499146 12.631 0.51212 12.6918 0.537327C12.7525 0.562533 12.8077 0.599476 12.8541 0.646039Z"
                      fill="#CB5715"
                      stroke="#CB5715"
                    />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M1.14592 0.646039C1.09935 0.692485 1.06241 0.747661 1.0372 0.808406C1.012 0.869151 0.999023 0.934272 0.999023 1.00004C0.999023 1.06581 1.012 1.13093 1.0372 1.19167C1.06241 1.25242 1.09935 1.30759 1.14592 1.35404L12.1459 12.354C12.2398 12.4479 12.3671 12.5007 12.4999 12.5007C12.6327 12.5007 12.76 12.4479 12.8539 12.354C12.9478 12.2602 13.0005 12.1328 13.0005 12C13.0005 11.8673 12.9478 11.7399 12.8539 11.646L1.85392 0.646039C1.80747 0.599476 1.7523 0.562533 1.69155 0.537327C1.63081 0.51212 1.56568 0.499146 1.49992 0.499146C1.43415 0.499146 1.36903 0.51212 1.30828 0.537327C1.24754 0.562533 1.19236 0.599476 1.14592 0.646039Z"
                      fill="#CB5715"
                      stroke="#CB5715"
                    />
                  </svg>
                  <span>Remove</span>
                </button>`
                : ``
            }
							</div>
						</article>
					</article>
  `;
  }
}
window.customElements.define('bag-item', BagItem);
