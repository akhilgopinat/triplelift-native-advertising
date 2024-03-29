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

(function($,window,undefined){var pluginName="prettyCheckable",document=window.document,defaults={label:"",labelPosition:"right",customClass:"",color:"blue"};function Plugin(element,options){this.element=element;this.options=$.extend({},defaults,options);this._defaults=defaults;this._name=pluginName;this.init()}function addCheckableEvents(element){if(window.ko){$(element).on("change",function(e){e.preventDefault();if(e.originalEvent===undefined){var clickedParent=$(this).closest(".clearfix");var fakeCheckable=$(clickedParent).find("a");var input=clickedParent.find("input");var isChecked=input.prop("checked");if(isChecked===true){fakeCheckable.addClass("checked")}else{fakeCheckable.removeClass("checked")}}})}element.find("a, label").on("touchstart click",function(e){e.preventDefault();var clickedParent=$(this).closest(".clearfix");var input=clickedParent.find("input");var fakeCheckable=clickedParent.find("a");if(input.prop("disabled")===true){return}if(input.prop("type")==="radio"){$('input[name="'+input.attr("name")+'"]').each(function(index,el){$(el).prop("checked",false).parent().find("a").removeClass("checked")})}if(window.ko){ko.utils.triggerEvent(input[0],"click")}else{if(input.prop("checked")){input.prop("checked",false).change()}else{input.prop("checked",true).change()}}fakeCheckable.toggleClass("checked")});element.find("a").on("keyup",function(e){if(e.keyCode===32){$(this).click()}})}Plugin.prototype.init=function(){var el=$(this.element);el.parent().addClass("has-pretty-child");el.css("display","none");var classType=el.data("type")!==undefined?el.data("type"):el.attr("type");var label=el.data("label")!==undefined?el.data("label"):this.options.label;var labelPosition=el.data("labelposition")!==undefined?"label"+el.data("labelposition"):"label"+this.options.labelPosition;var customClass=el.data("customclass")!==undefined?el.data("customclass"):this.options.customClass;var color=el.data("color")!==undefined?el.data("color"):this.options.color;var disabled=el.prop("disabled")===true?"disabled":"";var containerClasses=["pretty"+classType,labelPosition,customClass,color,disabled].join(" ");el.wrap('<div class="clearfix '+containerClasses+'"></div>').parent().html();var dom=[];var isChecked=el.prop("checked")?"checked":"";var isDisabled=el.prop("disabled")?true:false;if(labelPosition==="labelright"){dom.push('<a href="#" class="'+isChecked+'"></a>');dom.push('<label for="'+el.attr("id")+'">'+label+"</label>")}else{dom.push('<label for="'+el.attr("id")+'">'+label+"</label>");dom.push('<a href="#" class="'+isChecked+'"></a>')}el.parent().append(dom.join("\n"));addCheckableEvents(el.parent())};Plugin.prototype.disableInput=function(){var el=$(this.element);el.parent().addClass("disabled");el.prop("disabled",true)};Plugin.prototype.enableInput=function(){var el=$(this.element);el.parent().removeClass("disabled");el.prop("disabled",false)};$.fn[pluginName]=function(options){var inputs=[];this.each(function(){if(!$.data(this,"plugin_"+pluginName)){inputs.push($.data(this,"plugin_"+pluginName,new Plugin(this,options)))}});return inputs}}(jQuery,window));

</script>
