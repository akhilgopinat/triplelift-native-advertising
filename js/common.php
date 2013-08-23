<script type="text/javascript">
function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

function unable_to_contact(target_div) {
    jQuery("#"+target_div).html('<div class="updated" id="message"><p><strong>Error</strong>: Unable to contact TripleLift</p></div>');
}

function triplelift_np_admin_confirmation_links() {
	var elems = document.getElementsByClassName('triplelift_np_admin_confirmation');
	var confirmIt = function (e) {
		if (!confirm('Are you sure?')) e.preventDefault();
	};
	for (var i = 0, l = elems.length; i < l; i++) {
		elems[i].addEventListener('click', confirmIt, false);
	}
}
</script>
