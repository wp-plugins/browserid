function browserid_login() {
	navigator.id.getVerifiedEmail(function(assertion) {
		if (assertion) {
			rememberme = document.getElementById('rememberme');
			if (rememberme != null)
				rememberme = rememberme.checked;
			var url = browserid_siteurl + '?browserid_assertion=' + assertion + '&rememberme=' + rememberme;
			if (browserid_redirect != null)
				url += '&redirect_to=' + browserid_redirect;
			window.location.href = url;
		}
		else
			alert(browserid_failed);
	});
	return false;
}
