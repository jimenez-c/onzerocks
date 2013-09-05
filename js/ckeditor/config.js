/**
 * Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Just put the name of your custom skin here.
  //config.skin = 'moono-light';
  config.skin = 'moono-dark';
  
  config.filebrowserBrowseUrl = './js/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = './js/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = './js/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = './js/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = './js/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = './js/kcfinder/upload.php?type=flash';


// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
/*
config.toolbar = [	
	{ name: 'clipboard', items: [ 'Undo', 'Redo' ] },		
	{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
	{ name: 'paragraph',  items: [ 'BulletedList', '-', 'Blockquote' ] },
	{ name: 'links', items: [ 'Link', 'Unlink' ] },
	{ name: 'insert', items: [ 'Image', 'Smiley' ] },	
	{ name: 'styles', items: [ 'FontSize', 'TextColor' ] }	
];
  */
	config.toolbar = [	
		{ name: 'clipboard', items: [ 
			'Undo', 'Redo', 'Bold', 'Italic', 'Strike', 'RemoveFormat', 'BulletedList', 'Blockquote', 'Link', 'Unlink', 'Image', 'Smiley', 'FontSize', 'TextColor'
		] }				
	];  
};
