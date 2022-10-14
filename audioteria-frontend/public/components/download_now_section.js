class DownloadNowSection extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.innerHTML = `
 <section class="download-now-wrapper">
 <div>
		<div class="download-now">
			<div class="download-now-content">
				<h3 class="title">Download Now</h3>
				<p class="content">
					Lorem ipsum dolor sit amet, consectetur, Lorem ipsum dolor sit amet, consectetur
				</p>
			</div>
			<div class="buttons">
				<a title="google play" href="#">
					<img src="assets/google_play.png" />
				</a>
				<a title="app store" href="#">
					<img src="assets/app_store.png" />
				</a>
			</div>
		</div>
 </div>
	</section>      
    `;
  }
}
window.customElements.define('download-now-section', DownloadNowSection);
