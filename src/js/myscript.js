import 'code-prettify';

// Trigger function when window is loaded 
window.addEventListener("load", function() 
{
	PR.prettyPrint();

// Store tabs Variables
	var tabs = document.querySelectorAll("ul.nav-tabs > li");
// for everytime there is an emlement loop 
	for (var i = 0; i < tabs.length; i++) {
// Will run switchTab event every time a tab is clicked
		tabs[i].addEventListener("click", switchTab);
	}

	function switchTab(event) {
// stops adding extension in URL
		event.preventDefault();

// remove class active from nav-tabs active element
		document.querySelector("ul.nav-tabs li.active").classList.remove("active");	
// remove class active from tab-pane active element
		document.querySelector(".tab-pane.active").classList.remove("active");		

// variable for clicked event
		var clickedTab = event.currentTarget;
// variable for target in clicked event 
		var anchor = event.target;
// variable to store tab attribute
		var activePaneID = anchor.getAttribute("href");

// add class active to clicked event
		clickedTab.classList.add("active");
// adds active class to active tab
		document.querySelector(activePaneID).classList.add("active");
	}


});

// Deafult triggering of JQuery - use JQuery only when document is ready
jQuery(document).ready(function ($) {
// listen for click event on js-image-upload class on document then trigger function
	$(document).on('click', '.js-image-upload', function (e) {
// Prevent the deafult beahviour of the element 
		e.preventDefault();
// Variable to handle the instances of this click
		var $button = $(this);

// Variable to contain all the info on wp media uploader
		var file_frame = wp.media.frames.file_frame = wp.media({
// Attribute Title
			title: 'Select or Upload an Image',
// List of all the types of elements that the user can select
			library: {
				type: 'image' // mime type
			},
// Customise button text inside media uploader
			button: {
				text: 'Select Image'
			},
// The ability to select one or more images
			multiple: false
		});

// Tells the media uploader what to do with selected image
// event listen file.frame variable for when click select button, trigger function
		file_frame.on('select', function() {
// Variable to carry the file selected by user
// check selected then get the selected only the first one and convert to JSON format
			var attachment = file_frame.state().get('selection').first().toJSON();
// From the instance select only the sibling class .image-upload update value
			$button.siblings('.image-upload').val(attachment.url).trigger("change");
		});

// Trigger media uploader
		file_frame.open();
	});
});
