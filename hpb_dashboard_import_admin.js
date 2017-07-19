(function($){
	$.fn.extend({
		toggleButtons : function(callback){
			var radios = this;
 			radios.on("change", function(e){
				radios.filter(':not(:checked)').closest('label').removeClass("selected");
				radios.filter(':checked').closest('label').addClass("selected");
			});
			radios.closest("label").on("click", function(e){
				var input = $(this).find("input");
				if(!input.prop("checked")){
					input.prop("checked", true).trigger("change");
				}
			});
		}
	});
	$("input[type=radio]").toggleButtons();
})(jQuery);