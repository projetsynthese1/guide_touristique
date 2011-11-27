var mediaUploader = {
	OptionUploaderUseThisImage : function(id,target){
		var win = window.dialogArguments || opener || parent || top;
		win.theme.themeOptionGetImage(id,target);
		win.tb_remove();
	}
}
