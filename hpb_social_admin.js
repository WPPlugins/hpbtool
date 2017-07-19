jQuery(document).ready(function($){
	$('#hpb_social_tabs').tabs();

	if($("#socialbuttonlist").children("li").length == 0 ) {
		$("#socialbuttonlist").addClass("guidance_drop");
	}

	function updateSocialButtonList(){
		var data=[];
		$("li","#socialbuttonlist").each(function(i,v){
			data.push($(v).children("img").attr("id"));
		});
		$('#order').attr("value", data.toString());
		if($("#socialbuttonlist").children("li").length == 0 ) {
			$("#socialbuttonlist").addClass("guidance_drop");
		} else {
			$("#socialbuttonlist").removeClass("guidance_drop");
		}
	}

	$("#socialbuttonlist").sortable({
		connectWith: '#trashbox',
		update: function(){
			updateSocialButtonList();
		}
	})
	.click(function(e){
		var target = $(e.target).closest('#socialbuttonlist > li');
		if( target.length == 0 ){
			return;
		}
		if( target.is('.ui-draggable-dragging') ){
			return;
		}
		var src = target.children('img').attr('src');
		var div_dialog = jQuery('<div class="ui-dialog" ><p class="hpb_social_button"><img src="' + src + '" /></p></div>');
		div_dialog.dialog({
			autoOpen: false,
			modal: true,
			draggable: false,
			resizable: false,
			title: 'ソーシャルボタン',
			closeOnEscape: true,
			dialogClass: 'dialog_add_social_button_confirm wp-dialog',
			buttons: {
				"削除": function(){
					target.remove();
					$("#socialbuttonlist").sortable("refresh");
					updateSocialButtonList();
					$(this).dialog('close');
				},
				"キャンセル": function(){
					$(this).dialog('close');
				}
			}
		});
		div_dialog.dialog('open');
		return false;
	});
	$("#trashbox").droppable({
		accept: '#socialbuttonlist > li',
         	activeClass: 'ui-state-active',
         	hoverClass: 'ui-state-hover',
		 over: function(ev, ui) {
	 		$(".over", this).fadeIn();
		},
		 out: function(ev, ui) {
	 		$(".over", this).fadeOut();
		},
		 drop: function(event, ui) {
	 		$(".over", this).fadeOut();
			deleteImage(ui.draggable,ui.helper);
		}
 	});

	function deleteImage($draggable,$helper){
 		$draggable.remove();
 	};

	$("ul.buttonTypes > li")
	.draggable({
	 	 helper: 'clone',
		 connectToSortable: '#socialbuttonlist'
	})
	.click(function(){
		if( $(this).is('.ui-draggable-dragging') ){
			return;
		}
		var target = $(this);
		var src = $(this).children('img').attr('src');
		var div_dialog = jQuery('<div class="ui-dialog" ><p class="hpb_social_button"><img src="' + src + '" /></p></div>');
		div_dialog.dialog({
			autoOpen: false,
			modal: true,
			draggable: false,
			resizable: false,
			title: 'ソーシャルボタン',
			closeOnEscape: true,
			dialogClass: 'dialog_add_social_button_confirm wp-dialog',
			buttons: {
				"追加": function(){
					var copy = target.clone();
					copy.removeAttr('class');
					$("#socialbuttonlist").append(copy);
					$("#socialbuttonlist").sortable("refresh");
					updateSocialButtonList();
					$(this).dialog('close');
				},
				"キャンセル": function(){
					$(this).dialog('close');
				}
			}
		});
		div_dialog.dialog('open');
		return false;
	});
});