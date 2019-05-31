window.addEventListener('load', function() {

	var warning = document.createElement('div'); 
	warning.innerHTML = omiseText.warning + ' - \
		<a target="_blank" href="https://www.omise.co/what-are-test-keys-and-live-keys">\
		[ ' + omiseText.linkText +' ]</a>';
	warning.className = 'omise-test-warning';
	document.body.appendChild(warning);

});
