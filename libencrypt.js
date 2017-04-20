function encrypt() {
	if (window.crypto.getRandomValues)
	{
		if ($('#message').val().length == 0)
		{
			window.alert('Vous ne voulez vraiment pas écrire un petit quelque chose ? :(');
			return false;
		}
		require("openpgp.min.js");
		openpgp.init();
		var pub_key = openpgp.read_publicKey($('#pubkey').text());
		$('#message').val(openpgp.write_encrypted_message(pub_key,$('#message').val()));
		window.alert("Ce message chiffré va être envoyé :\n" + $('#message').val());
		return true;
	}
	else
	{
		$("#send").val("browser not supported");
		window.alert("Error: Browser not supported\nReason: We need a cryptographically secure PRNG to be implemented (i.e. the window.crypto method)\nSolution: Use Chrome >= 11, Safari >= 3.1, Firefox >= 21 or Opera >= 12");   
		return false;
	}
}

function require(script) {
	$.ajax({
		url: script,
		dataType: "script",
		async: false,           // <-- this is the key
		success: function () {
			// all good ...
		},
		error: function () {
			throw new Error("Could not load script " + script);
		}
	});
}
