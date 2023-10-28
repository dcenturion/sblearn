
 	// var protocol = window.location.protocol;
	// var hostname = window.location.hostname;
	// var url = ""+protocol+"//"+hostname+"/splugin/ckeditor/upload.php";
	// alert("Hola"+url);
	
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// config.resourceType[2].url = config.baseUrl & 'images';
    // config.resourceType[2].directory = config.baseDir & 'images';
	var protocol = window.location.protocol;
	var hostname = window.location.hostname;
	config.filebrowserUploadUrl = ""+protocol+"//"+hostname+"/splugin/ckeditor/upload.php";
};

