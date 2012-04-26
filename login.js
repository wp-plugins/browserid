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

			var rememberme_field = document.createElement('input');
			rememberme_field.type = 'hidden';
			rememberme_field.name = 'rememberme';
			rememberme_field.value = rememberme;
			form.appendChild(rememberme_field);

			if (browserid_redirect != null) {
				var redir_field = document.createElement('input');
				redir_field.type = 'hidden';
				redir_field.name = 'redirect_to';
				redir_field.value =  browserid_redirect;
				form.appendChild(redir_field);
			}
			var assertion_form = document.body.appendChild(form);
			assertion_form.submit();
		}
		else
			alert(browserid_failed);
	});
	return false;
}
