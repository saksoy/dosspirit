// Game group spesific
$('.addNewGroup').bind('click', function() {
	$('.groupName').first().clone().appendTo('.groupNames');
	//$('.groupnames').append('<br />');
});

// New review related
$('.editorNorwegian').markItUp(myBbcodeSettings);
$('.editorEnglish').markItUp(myBbcodeSettings);
    
$('.emoticons span').click(function() {
        emoticon = $(this).attr("title");
        $.markItUp( { replaceWith:emoticon } );
});
    
$('#reviewPreview').bind('click', function() {
        $(this).hide();
});