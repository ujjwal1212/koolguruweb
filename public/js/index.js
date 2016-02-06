// Dropdown Menu Fade    
jQuery(document).ready(function() {
   setupAllDropDown();
   setPageValidations();
   setLoginValidations();
});
function setupAllDropDown(){
	 $(".dropdown").hover(
            function() {
                $('.dropdown-menu', this).fadeIn("fast");
            },
            function() {
                $('.dropdown-menu', this).fadeOut("fast");
            }
    );
    $(".mega-menu-close").click(function() {
        $('.mega-menu').css("display", "none");
    });
    // Adding select2 for paging dropdown
	
    $("#items_per_page").select2({minimumResultsForSearch: -1})
	$("select[name='role']").select2({minimumResultsForSearch: -1})
}


/* Showing and Hiding of Global Validation Messages by Gargi - this is temporary solution till the dev team starts working on it. this needs to be implemented in a function, and the function would be called once the validations messages in the div is loaded*/
function setPageValidations(){
	var gSuccessMsg = $("#global-success-msg");
	var gErrorMsg = $("#global-error-msg");

	if ($(gSuccessMsg).text().length > 0){
		$(gSuccessMsg).animate({ opacity:1, top: "-26px"}, 1000);
		$(gSuccessMsg).delay(3000).fadeOut("slow");
	}
	
	if ($(gErrorMsg).text().length > 0){
		$(gErrorMsg).animate({ opacity:1, top: "-26px"}, 1000);
		$(gErrorMsg).delay(3000).fadeOut("slow");
	}
}


/*Specific for login pages as the top position varies for the validation messages */
function setLoginValidations(){

	var gSuccessMsgPage = $("#global-success-msg-page");
	var gErrorMsgPage = $("#global-error-msg-page");

	if ($(gSuccessMsgPage).text().length > 0){
		$(gSuccessMsgPage).animate({ opacity:1, top: "60px" }, 1000);
		$(gSuccessMsgPage).delay(3000).fadeOut("slow");
	}
	
	if ($(gErrorMsgPage).text().length > 0){
		$(gErrorMsgPage).animate({ opacity:1, top: "60px" }, 1000);
		$(gErrorMsgPage).delay(3000).fadeOut("slow");
	}
}



