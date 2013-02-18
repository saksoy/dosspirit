<div class="gameReputation">
    <span id="likeGame" title="I like this game!" class="generalButton roundedCorner defaultShadow gameRep">
        <img alt="like icon" src="/images/icon/heart.png">
        <?php echo __('I like this game') . ' (<span>' . count($gameReputation['likes']) . '</span>)'; ?>
    </span>
    <span id="playedGame" title="Oh yes, I've played this one!" class="generalButton roundedCorner defaultShadow gameRep">
        <img alt="joystick icon" src="/images/icon/joystick.png">
        <?php echo __('I have played this game') . ' (' . count($gameReputation['played']) . ')'; ?>
    </span>
    <span id="ownsGame" title="I own this game!" class="generalButton roundedCorner defaultShadow gameRep">
        <img alt="floppy icon" src="/images/icon/save.png">
        <?php echo __('I own this game') . ' (' . count($gameReputation['owns']) . ')'; ?>
    </span>
    <span id="wantGame" title="Want this game, naow!" class="generalButton roundedCorner addToCollection defaultShadow gameRep">
        <img alt="present icon" src="/images/icon/present.png">
        <?php echo __('I want this game') . ' (' . count($gameReputation['want']) . ')'; ?>
    </span>
    <span id="addGameToCollection" title="Add game to my collection (Need to be logged in)" class="generalButton roundedCorner defaultShadow">
        <img alt="add collection" src="/images/icon/plus.png">
        <?php echo __('Add to my collection'); ?>
    </span>
    <input type="hidden" id="gameId" value="<?php echo $gameId; ?>" />
    <input type="hidden" id="gameSlug" value="<?php echo $gameSlug; ?>" />

    <div id="gameReputationFeedback"></div>
</div>

<script type="text/javascript">
$(function() {
    var gameId = $('input#gameId').val();
    var gameSlug = $('input#gameSlug').val();

	$('span.gameRep').bind('click', function() {
		var el = $(this);
        /*var elCountContainer = el.find('span');
        var elCount = parseInt(elCountContainer.text());*/

		$.ajax({
			url: '/ajax/gameinteraction',
			type: 'post',
			data: {
                type: 'gameRep',
                dataSent: $(this).attr('id'),
                gameId: gameId,
                gameSlug: gameSlug
            },
			success: function(data) {
			    //elCountContainer.text(elCount + 1);
				$('div#gameReputationFeedback').html(data);
			}
		});
	});

	$('span#addGameToCollection').bind('click', function() {
	    var gameSlug = $('input#gameSlug').val();
	    $.ajax({
			url: '/ajax/gameinteraction',
			type: 'post',
			data: {
                type: 'addGameToCollection',
                dataSent: $(this).attr('id'),
                gameId: gameId,
                gameSlug: gameSlug
            },
			success: function(data) {
				$('div#gameReputationFeedback').html(data);
			}
		});
	});
});
</script>