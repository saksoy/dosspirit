<!-- Only displays attached media without checkboxes in a regular fashion. -->
<div class="mediaListContainer hidden" id="attachedMediaList">

<?php
$attachedMediaCount = count($mediaList);


// Do some internal shifting so that it's easier to present by category
$r = array();
foreach ($mediaList as $entry) {
            $r[$entry['type']][] = $entry;
}

$mediaList = $r;

echo '<p>' . __('There are %s attached media files.', array($attachedMediaCount)) . '</p>';


foreach ($mediaList as $mediaTypeName => $mediaType) {
    echo '<h4 id="' . $mediaTypeName .'" class="attachedMediaTypeHeading pointer">' . ucfirst($mediaTypeName) . '(s) - ' . count($mediaType) . ' files</h4>
    <ul class="mediaList hidden" id="' . $mediaTypeName .'-list">';
    foreach ($mediaType as $key => $media) {

        echo '<li>';
        if ($media['type'] == 'gamefile') {
            echo '<p><strong>' . $media['filename'] . '</strong></p>';
            $mediaPoolUrl = '/' . IMAGES_URL . '/gamefile.png';
        } else {
            $mediaPoolUrl = '/' . IMAGES_URL . 'game' . '/' . $media['filename'];
        }
        $mediaComment = $media['comment'] . ' - Created: ' . $media['created'];

        echo '<img src="' . $mediaPoolUrl . '" width="40" /></label>';

        echo '<p><a href="' . $mediaPoolUrl . '" rel="lightbox-media" title="' . $mediaComment .'" />' . __('Bigger preview') . '</a></p>
        </li>';
    }
    echo '</ul>';
}
?>
</div>
<span class="button" id="showAttachedMediaList">Toggle existing media <?php echo '(' . $attachedMediaCount . ' files)'; ?></span>

<script type="text/javascript">
$(document).ready(function() {
    $('.attachedMediaTypeHeading').bind('click', function() {
        $('ul#' + $(this).attr('id') + '-list').toggle();
    });

    $('span#showAttachedMediaList').bind('click', function() {
        $('div#attachedMediaList').toggle();
    });
});
</script>