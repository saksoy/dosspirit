
<script type="text/javascript">
$(document).ready(function() {
    $('.reviewText, .reviewTeaserText').markItUp(myBbcodeSettings);

    $('body').on('.modal', 'click', function() {
        $(this).hide();
    });

    $('.emoticons span').click(function() {
        emoticon = $(this).attr('title');
        $.markItUp( { replaceWith:emoticon } );
    });
});
</script>

<div id="reviewPreview" class="modal"></div>

<?php
if (isset($validationErrors)) {
    echo $this->element('validationErrors', array('validationErrors' => $validationErrors));
}
?>
<h3><?php echo __('Add review'); ?></h3>
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
    $ratingArray['N/A'] = 'N/A';
    for ($i = (float) 0.5; $i <= 6; $i+= 0.5) {
        $ratingArray[(string)$i] = $i;
    }

    echo '<h3>' . __('Edit review for "%s"', array($this->data['Game']['name'])) . '</h3>';
    echo $this->Form->create('Review',
        array(
        	'type' => 'post',
        	'inputDefaults' => array(
        		'label' => false,
        		'div' => false,
                'id' => false
        )
    ));
    echo '<h5>' . __('Review publication status') . '</h5>';
    echo $this->Form->input('Review.user_id', array('type' => 'hidden'));
    echo $this->Form->select('Review.draft', array(1 => 'Draft', 0 => 'Published'), array('empty' => false));
    echo '<h5>' . __('Review teaser text') . '</h5>';
    echo $this->Form->input('Review.teaser_text', array('type' => 'textarea', 'cols' => 90, 'rows' => 5, 'class' => 'reviewTeaserText'));
    echo '<h5>' . __('Review text') . '</h5>';
    echo $this->Form->input('Review.text', array('type' => 'textarea', 'cols' => 90, 'rows' => 10, 'class' => 'reviewText'));
    echo $this->Form->input('Review.id', array('type' => 'hidden'));
    echo $this->Form->input('Review.game_id', array('type' => 'hidden'));
    echo $this->Form->input('Review.total', array('type' => 'hidden'));
    echo '<h5>' . __('This review is written in this language') . '</h5>';
    echo $this->Form->select('Review.language', array('nor' => 'Norwegian', 'eng' => 'English'), array('empty' => false));
?>

<div class="contentColumn">
<h3>Publish date</h3>
<?php
    echo $this->Form->input('Review.publish_date', array(
    	'type' => 'datetime',
        'dateFormat' => 'DMY',
        'timeFormat' => '24',
        'minYear' => '2005',
        'maxYear' => date('Y', time()) + 1,
    ));
?>

<h3>Award Golden DOS Spirit</h3>
<?php
    echo $this->Form->select('golden', array(1 => 'Yes', 0 => 'No'), array('empty' => false));
?>

<h3>Score</h3>
    <img alt="icon" src="/images/icon/graphics.png">Graphics: <?php echo $this->Form->select('Review.rating.graphics', $ratingArray, array('empty' => false)); ?><br />
    <img alt="icon" src="/images/icon/sound.png">Sound: <?php echo $this->Form->select('Review.rating.sound', $ratingArray, array('empty' => false)); ?><br />
    <img alt="icon" src="/images/icon/gameplay.png">Gameplay: <?php echo $this->Form->select('Review.rating.gameplay', $ratingArray, array('empty' => false)); ?><br />
    <img alt="icon" src="/images/icon/story.png">Story: <?php echo $this->Form->select('Review.rating.story', $ratingArray, array('empty' => false)); ?><br />
    <img alt="icon" src="/images/icon/difficulty.png">Difficulty: <?php echo $this->Form->select('Review.rating.difficulty', $ratingArray, array('empty' => false)); ?><br />
    <img alt="icon" src="/images/icon/learningcurve.png">Learning Curve: <?php echo $this->Form->select('Review.rating.learningcurve', $ratingArray, array('empty' => false)); ?><br />
    Total: <?php echo $this->Form->select('Review.total', $ratingArray, array('empty' => false)); ?>

<?php
    echo $this->Form->end('Edit review');
?>
</div>