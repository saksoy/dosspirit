
<script type="text/javascript">
$(document).ready(function() {
    $('.reviewText, .reviewTeaserText').markItUp(myBbcodeSettings);

    $('.modal').live('click', function() {
        $(this).hide();
    });

    $('.emoticons span').click(function() {
        emoticon = $(this).attr('title');
        $.markItUp( { replaceWith:emoticon } );
    });
});
</script>

<div id="previewPane" class="modal"></div>
<?php
    $ratingArray['N/A'] = 'N/A';
    for ($i = (float) 0.5; $i <= 6; $i+= 0.5) {
        $ratingArray[(string)$i] = $i;
    }

    echo '<h3>' . __('Add review for %s', array($gameName)) . '</h3>';
    echo $this->Form->create('Review',
        array(
        	'type' => 'post',
        	'inputDefaults' => array(
        		'label' => false,
        		'div' => false,
                'id' => false
        )
    ));

    echo '<h5>' . __('Publish date') . '</h5>';
    echo $this->Form->input('Review.publish_date', array(
        'type' => 'datetime',
        'dateFormat' => 'DMY',
        'timeFormat' => '24',
        'selected' => date('Y-m-d 12:00', time()),
        'minYear' => date('Y', time()),
        'maxYear' => date('Y', time()) + 1,
    ));

    echo '<h5>' . __('Publication status') . '</h5>';
    echo $this->Form->select('Review.draft', array(1 => 'Draft', 0 => 'Published'), array('empty' => false));
    echo '<h5>' . __('Review is written in this language') . '</h5>';
    echo $this->Form->select('Review.language', array('nor' => 'Norwegian', 'eng' => 'English'), array('empty' => false));
    echo '<h4>' . __('Review teaser text') . '</h4>';
    echo $this->Form->input('Review.teaser_text', array('type' => 'textarea', 'cols' => 90, 'rows' => 5, 'class' => 'reviewTeaserText'));
    echo '<h4>' . __('Review text') . '</h4>';
    echo $this->Form->input('Review.text', array('type' => 'textarea', 'cols' => 90, 'rows' => 10, 'class' => 'reviewText'));

?>

    <div class="emoticons">
        <h4>Emoticons</h4>
        <span title="[emo]lol[/emo]"><img src="/images/icon/emoticon/lol.png" /></span>
        <span title="[emo]smile[/emo]"><img src="/images/icon/emoticon/smile.png" /></span>
        <span title="[emo]surprise[/emo]"><img src="/images/icon/emoticon/surprise.png" /></span>
        <span title="[emo]tongue[/emo]"><img src="/images/icon/emoticon/tongue.png" /></span>
        <span title="[emo]unhappy[/emo]"><img src="/images/icon/emoticon/unhappy.png" /></span>
        <span title="[emo]wink[/emo]"><img src="/images/icon/emoticon/wink.png" /></span>
    </div>

    <?php
    //echo $this->Form->input('Review.date', array('type' => 'hidden'));
    echo $this->Form->input('Review.modified', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', time())));
    //echo $this->Form->input('user_id', array('type' => 'hidden'));
    echo $this->Form->input('Review.id', array('type' => 'hidden'));
    echo $this->Form->input('Review.game_id', array('type' => 'hidden'));
    echo $this->Form->input('Review.total', array('type' => 'hidden'));
?>

<h3>Award Golden DOS Spirit</h3>
<?php
    echo $this->Form->select('golden', array(1 => 'Yes', 0 => 'No'), array('empty' => false));
?>

<h3>Score</h3>
<ul class="cleanList cleanCardList ">
    <li>Graphics: <?php echo $this->Form->select('Review.rating.graphics', $ratingArray, array('empty' => false)); ?></li>
    <li>Sound: <?php echo $this->Form->select('Review.rating.sound', $ratingArray, array('empty' => false)); ?></li>
    <li>Gameplay: <?php echo $this->Form->select('Review.rating.gameplay', $ratingArray, array('empty' => false)); ?></li>
    <li>Story: <?php echo $this->Form->select('Review.rating.story', $ratingArray, array('empty' => false)); ?></li>
    <li>Difficulty: <?php echo $this->Form->select('Review.rating.difficulty', $ratingArray, array('empty' => false)); ?></li>
    <li>Learning Curve: <?php echo $this->Form->select('Review.rating.learningcurve', $ratingArray, array('empty' => false)); ?></li>
    <li>Total: <?php echo $this->Form->select('Review.total', $ratingArray, array('empty' => false)); ?></li>
</ul>

<?php
    echo $this->Form->end('Add / Modify');
?>
