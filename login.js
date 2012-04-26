function browserid_login() {
	navigator.id.getVerifiedEmail(function(assertion) {
		if (assertion) {
			var rememberme = document.getElementById('rememberme');
			if (rememberme != null)
				rememberme = rememberme.checked;

			var form = document.createElement('form');
			form.setAttribute('style', 'display: none;');
			form.method = 'POST';
			form.action = browserid_siteurl;

			var fields =
				[{name: 'browserid_assertion', value: assertion},
				{name: 'rememberme', value: rememberme}];

			if (browserid_redirect != null)
				fields.push({name: 'redirect_to', value: browserid_redirect});

			for (var i = 0; i < fields.length; i++) {
				var field = document.createElement('input');
				field.type = 'hidden';
				field.name = fields[i].name;
				field.value = fields[i].value;
				form.appendChild(field);
			}

			document.body.appendChild(form).submit();
		}
		else
			alert(browserid_failed);
	});
	return false;
}
