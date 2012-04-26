//function browserid_login() {
//	navigator.id.getVerifiedEmail(function(assertion) {
//		if (assertion) {
//			rememberme = document.getElementById('rememberme');
//			if (rememberme != null)
//				rememberme = rememberme.checked;
//			var url = browserid_siteurl + '?browserid_assertion=' + assertion + '&rememberme=' + rememberme;
//			if (browserid_redirect != null)
//				url += '&redirect_to=' + browserid_redirect;
//			window.location.href = url;
//		}
//		else
//			alert(browserid_failed);
//	});
//	return false;
//}

function browserid_login() {
	navigator.id.getVerifiedEmail(function(assertion) {
		if (assertion) {
			rememberme = document.getElementById('rememberme');
			if (rememberme != null)
				rememberme = rememberme.checked;
			var form = document.createElement('form');
			form.method = 'POST';
			form.action = browserid_siteurl;
			form.id = 'assertion_send_form';

			var assertion_field = document.createElement('input');
			assertion_field.type = 'hidden';
			assertion_field.name = 'browserid_assertion';
			assertion_field.value = assertion;
			form.appendChild(assertion_field);

			var remem_field = document.createElement('input');
			remem_field.type = 'hidden';
			remem_field.name = 'rememberme';
			remem_field.value = rememberme;
			form.appendChild(remem_field);

			if (browserid_redirect != null) {
				var remem_field = document.createElement('input');
				redir_field.type = 'hidden';
				redir_field.name = 'redirect_to';
				redir_field.value =  browserid_redirect;
				form.appendChild(redir_field);
			}
			var my_form = document.body.appendChild(form);
			my_form.submit();
		}
		else
			alert(browserid_failed);
	});
	return false;
}
