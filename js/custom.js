        //##########################################################################//
	// Scrolling background Tutorial Code by http://www.kudoswebsolutions.com
        // Original Source code: http://youlove.us/
        // Modified and extended by Kudos Web Solutions
        // copyright 2009 kudoswebsolutions.com
        //##########################################################################//

$(function() {
	// Define the height of your two background images.
           //The image to scroll
	   var backgroundheight = 2000;
           //The image to overlay
	   var backgroundheight_two = 1000;

	// Create a random offset for both images' starting positions
        offset = Math.round(Math.floor(Math.random()* 2001));
        offset2 = Math.round(Math.floor(Math.random()* 1001));
 
	function scrollbackground() {
                //Ensure all bases are covered when defining the offset.
   		offset = (offset < 1) ? offset + (backgroundheight - 1) : offset - 1;
		// Put the background onto the required div and animate vertically
   		$('#container').css("background-position", "50% " + offset + "px");
   		// Enable the function to loop when it reaches the end of the image
   		setTimeout(function() {
			scrollbackground();
			}, 100
		);
   	}
	function scrollbackground2() {
                //Ensure all bases are covered when defining the offset.
   		offset = (offset < 1) ? offset + (backgroundheight - 1) : offset - 1;
		// Put the background onto the required div and animate vertically
   		$('#container2').css("background-position", "50% " + offset + "px");
   		// Enable the function to loop when it reaches the end of the image
   		setTimeout(function() {
			scrollbackground2();
			}, 100
		);
   	}

	// Initiate the scroll
	scrollbackground();
	scrollbackground2();

	// Use the offset defined earlier and apply it to the div.
   		$('#overlay').css("background-position", "50% " + offset2 + "px");
   		$('#overlay2').css("background-position", "50% " + offset2 + "px");
});