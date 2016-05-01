CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.allowedContent = true;
};



CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar_Book = [['eqneditor']];
                                                                                                
    
	//config.autoGrow_minHeight = '450';
	//config.autoGrow_maxHeight = 800;
	config.extraPlugins = 'eqneditor';
    config.removePlugins = '';
    //config.justifyClasses = [ 'AlignLeft', 'AlignCenter', 'AlignRight', 'AlignJustify' ];
	//config.contentsCss	= app.base_url + '/sites/all/modules/community/ckeditor/ckeditor/contents.css';
	//config.width 		= '99.9%';
		
	//config.toolbarCanCollapse 	= false;
	//config.resize_enabled 		= true;	
	//config.removePlugins 		= 'elementspath' ;
};
