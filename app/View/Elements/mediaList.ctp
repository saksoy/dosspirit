<div class="mediaListContainer" id="mediaList">
<?php
$mediaPoolCount = count($mediaList);

// Do some internal shifting so that it's easier to present by category
$r = array();
foreach ($mediaList as $entry) {
            $r[$entry['MediaPool']['type']][] = $entry;
}

$mediaList = $r;

echo '<p>' . __('Database contains %s media files.', array($mediaPoolCount)) . '</p>';

?>
<span class="generalButton defaultShadow" id="selectAllButton">Select all</span>
<span class="generalButton defaultShadow" id="deselectAllButton">Deselect all</span>
<p><input type="text" size="50" id="mediaListSearch" placeholder="Type in something to search..." /></p>

<span>You have selected <strong><span id="mediaSelectCounter">0</span></strong> media file(s).</span>
<?php

foreach ($mediaList as $mediaTypeName => $mediaType) {
    echo '<h4 id="' . $mediaTypeName .'" class="mediaTypeHeading pointer">' . ucfirst($mediaTypeName) . '(s) - ' . count($mediaType) . ' files</h4>
    <ul class="mediaList hidden" id="' . $mediaTypeName .'-list">';
    foreach ($mediaType as $key => $media) {

        echo '<li title="' . $media['MediaPool']['comment'] . '"><label>';
        echo $this->Form->input('MediaPool.selected',
            array(
                'id' => 'MediaPool-' . $key,
                'type' => 'checkbox',
                'class' => 'mediaSelect',
                'label' => 'Choose this <br />',
                'hiddenField' => false,
                'value' => $media['MediaPool']['id'],
                'name' => 'data[MediaPool][selected][]'
            )
        );

        if ($media['MediaPool']['type'] == 'gamefile') {
            echo '<p><strong>' . $media['MediaPool']['name'] . '</strong></p>';
            $mediaPoolUrl = '/' . IMAGES_URL . '/gamefile.png';
        } else {
            $mediaPoolUrl = '/' . IMAGES_URL . 'mediapool' . '/' . $media['MediaPool']['name'];
        }
        $mediaComment = $media['MediaPool']['comment'] . ' - Created: ' . $media['MediaPool']['created'] . ' by ' .$media['User']['username'];

        echo '<img src="' . $mediaPoolUrl . '" width="140" /></label>
        <p><span title="' . $media['MediaPool']['comment'] . '">' . $media['MediaPool']['comment'] . '</span><br />
        <a href="' . $mediaPoolUrl . '" rel="lightbox-media" title="' . $mediaComment .'" />' . __('Bigger preview') . '</a></p>
        </li>';
    }
    echo '</ul>';
}
?>
</div>


<span class="button" id="showMediaList">Toggle media list <?php echo '(' . $mediaPoolCount . ' files available)'; ?></span>


<script type="text/javascript">
$(document).ready(function() {
    var selMedia = 0;

    $.extend($.expr[":"], {
        "contains-ci": function(elem, i, match, array) {
            return ($(elem).attr('title') || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    $('#selectAllButton').bind('click', function() {
        $('ul.mediaList li input[type="checkbox"]').prop('checked', true);
        updateSelectedMedia();
    });

    $('.mediaSelect').bind('click', function() {
        updateSelectedMedia();
    });

    var updateSelectedMedia = function() {
        selMedia = $('ul.mediaList li input[type="checkbox"]:checked').length;
        $('span#mediaSelectCounter').text(selMedia);
    };

    $('#deselectAllButton').bind('click', function() {
        $('ul.mediaList li input[type="checkbox"]').prop('checked', false);
        updateSelectedMedia();
    });

    $('.mediaTypeHeading').bind('click', function() {
        $('ul#' + $(this).attr('id') + '-list').toggle();
    });

    $('span#showMediaList').bind('click', function() {
        $('div#mediaList').toggle();
    });

    $('input#mediaListSearch').keyup(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }

        var value = $(this).val();
        if (value.length > 2) {
            $('ul.mediaList li:not(:contains-ci("' + value + '"))').hide();
            $('ul.mediaList li:contains-ci("' + value + '")').show();
        } else {
            $('ul.mediaList li').show();
        }
    });
});
</script>