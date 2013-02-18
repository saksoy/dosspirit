<?php echo '<strong>' . __('Rate this game') . '</strong>'; ?>
<select id="gameVote" class="selectbox rounderCorner" name="gameVote">
    <option value="" selected="selected" ><?php echo __('Choose a rating'); ?></option>
    <option value="1">1 - <?php echo __('Abysmal'); ?></option>
    <option value="2">2 - <?php echo __('Below average'); ?></option>
    <option value="3">3 - <?php echo __('Ok'); ?></option>
    <option value="4">4 - <?php echo __('Good'); ?></option>
    <option value="5">5 - <?php echo __('Very good'); ?></option>
    <option value="6">6 - <?php echo __('Superb!'); ?></option>
</select>
<input type="hidden" id="gameId" value="<?php echo $gameId; ?>" />
<input type="hidden" id="gameSlug" value="<?php echo $gameSlug; ?>" />

<div id="voteFeedback"></div>
<script type="text/javascript">
$(function() {
	$('#gameVote').bind('change', function() {
		var selectedValue = $(this).val();

		if (selectedValue) {
    		var gameSlug = $('#gameSlug').val();
    		var gameId = $('#gameId').val();
    		var parent = $(this);

    		$.ajax({
    			url: '/ajax/gameinteraction',
    			type: 'post',
    			data: {
                    type: 'gameVote',
                    dataSent: selectedValue,
                    gameId: gameId,
                    gameSlug: gameSlug
                },
    			success: function(data) {

    				parent.hide();
    				if (data != 0) {
    					$('#voteFeedback').html(data);
    				} else {
    					$('#voteFeedback').text('Uh oh, something is wrong. We are on it!');
    				}
    			}
    		});
		}
	});
});
</script>