$(document).ready(function()
{
	//$(".accordion h3:first").addClass("active");
	//$(".accordion p:not(:first)").hide();
	$(".accordion p").hide();
	$(".accordion h3").click(function()
	{
		$(this).next("p").slideToggle("slow")
		.siblings("p:visible").slideUp("slow");
		$(this).toggleClass("active");
		$(this).siblings("h3").removeClass("active");
	});
});