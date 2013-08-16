$(function() {
	$("#uploader").pluploadQueue({
		// General settings
		runtimes : 'html5,gears,browserplus,silverlight,flash,html4',
		url : 'http://six-sigma.tv/test/wp-uploads.php',
		max_file_size : '100mb',

		unique_names : true,

		// Resize images on clientside if we can
		//resize : {width : 320, height : 240, quality : 90},

		// Specify what files to browse for
		filters : [
			{title : "video files", extensions : "avi,flv,mov,mp4,mpeg,mpeg4,mpg,swf,wmv,vob,VOB,DAT,mkv,3gp,jpg"},
	
		],

		// Flash settings
		flash_swf_url : 'js/plupload.flash.swf',

		// Silverlight settings
		silverlight_xap_url : 'js/plupload.silverlight.xap'
	});

	// Client side form validation

});