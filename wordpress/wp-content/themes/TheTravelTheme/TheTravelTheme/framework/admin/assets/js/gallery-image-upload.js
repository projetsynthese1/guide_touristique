function themeImageInsertIntoGallery(id){
	var win = window.dialogArguments || opener || parent || top;
	win.themeGalleryGetImage(id);
	win.tb_remove();
}
