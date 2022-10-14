const findResults = async ( event ) => {
	event.preventDefault();
	const target = event.target;
	const s = target.value;

	if ( s.length < 2 ) {
		return;
	}

	const data = new FormData();

	data.append( 's', s );
	data.append( 'nonce', wp_ajax_info.nonce );
	data.append( 'action', 'audioteria_search' );

	let options = {
		method: 'POST',
		credentials: 'same-origin',
		body: data,
	}

    const fetch_request = await fetch(wp_ajax_info.ajax_url, options);
    let json_response = fetch_request.json();

	// to do when the design for search drop down is ready
    json_response.then((data) => {

    }).catch((error) => {

    })
};

const addToSearchPath = ( uri, key, value ) => {
	let re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
	let separator = uri.indexOf('?') !== -1 ? '&' : '?';

	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + '=' + value + '$2');
	}
	else {
		return uri + separator + key + '=' + value;
	}
}

document.addEventListener( 'DOMContentLoaded', () => {
	document.querySelector( 'header #search-form' ).addEventListener( 'keyup', findResults );

	//filter search immediately for only when screen is greater 1024px
	if (window.innerWidth > 1024) {
		document.querySelectorAll( '.search-filter-wrap input[name="genres"], .search-filter-wrap input[name="categories"], .search-filter-wrap input[name="ratings"]').forEach((element) => {
			element.addEventListener('change', (event) => {
				let values = [];
				const name = event.target.getAttribute('name');
				document.querySelectorAll('.search-filter-wrap input[name="' + name + '"]:checked').forEach((element) => {
					values.push(element.value);
				});

				const value = values.join(',');
				document.location.href = addToSearchPath(window.location.href, name, value);
			});
		});

		document.querySelector( '.search-results-sort select[name="order_by"]').addEventListener('change', (event) => {
			const value = event.target.value;
			const name = event.target.getAttribute('name');
			document.location.href = addToSearchPath(window.location.href, name, value);
		});
	} else {

		document.querySelector('.mobile-filter-search').addEventListener('click', (event) => {
			event.preventDefault();
			let path = window.location.href;

			document.querySelectorAll( '.search-filter-wrap input[name="genres"]').forEach((element) => {
				let genres = [];
				const name = element.getAttribute('name');
				document.querySelectorAll('.search-filter-wrap input[name="genres"]:checked').forEach((element) => {
					genres.push(element.value);
				});

				const value = genres.join(',');
				path = addToSearchPath(path, name, value);
			});

			document.querySelectorAll( '.search-filter-wrap input[name="categories"]').forEach((element) => {
				let categories = [];
				const name = element.getAttribute('name');
				document.querySelectorAll('.search-filter-wrap input[name="categories"]:checked').forEach((element) => {
					categories.push(element.value);
				});

				const value = categories.join(',');
				path = addToSearchPath(path, name, value);
			});


			document.querySelectorAll( '.search-filter-wrap input[name="ratings"]').forEach((element) => {
				let ratings = [];
				const name = element.getAttribute('name');
				document.querySelectorAll('.search-filter-wrap input[name="ratings"]:checked').forEach((element) => {
					ratings.push(element.value);
				});

				const value = ratings.join(',');
				path = addToSearchPath(path, name, value);
			});

			document.location.href = path;
		})
	}

} );
