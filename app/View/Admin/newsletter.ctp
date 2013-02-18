<h1>Send newsletter</h1>

<?php
    echo $this->Form->create('Newsletter', array('inputDefaults' => array('div' => false, 'label' => false)));
    echo '<h4>Heading</h4>';
    echo $this->Form->input('heading', array('size' => 100));
?>
<div class="emoticons">
    <h4>Emoticons</h4>
    <span title="[emo]lol[/emo]"><img src="http://www.dosspirit.net/images/icon/emoticon/lol.png" /></span>
    <span title="[emo]smile[/emo]"><img src="http://www.dosspirit.net/images/icon/emoticon/smile.png" /></span>
    <span title="[emo]surprise[/emo]"><img src="http://www.dosspirit.net/images/icon/emoticon/surprise.png" /></span>
    <span title="[emo]tongue[/emo]"><img src="http://www.dosspirit.net/images/icon/emoticon/tongue.png" /></span>
    <span title="[emo]unhappy[/emo]"><img src="http://www.dosspirit.net/images/icon/emoticon/unhappy.png" /></span>
    <span title="[emo]wink[/emo]"><img src="http://www.dosspirit.net/images/icon/emoticon/wink.png" /></span>
</div>

<h4>Newsletter content</h4>
<?php
    echo $this->Form->input('content', array('type' => 'textarea', 'cols' => 100, 'rows' => 15));
    echo $this->Form->end('Send!');
?>
<p><em>You can use HTML here, but no evil-ness! (They get stripped out)</em></p>

<div id="previewPane" class="modal"></div>

<script type="text/javascript">
$(document).ready(function() {
    $('#NewsletterContent').markItUp(myBbcodeSettings);

    $('.modal').live('click', function() {
        $(this).hide();
    });

    $('.emoticons span').click(function() {
        emoticon = $(this).attr('title');
        $.markItUp( { replaceWith:emoticon } );
    });
});
</script>