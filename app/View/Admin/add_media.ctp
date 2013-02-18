<script type="text/javascript">
$(document).ready(function() {
    function dragenter(e) {
        e.stopPropagation();
        e.preventDefault();
      }

    function dragover(e) {
        e.stopPropagation();
        e.preventDefault();
    }

    function drop(e) {
        e.stopPropagation();
        e.preventDefault();

        var dt = e.dataTransfer;
        var files = dt.files;

        handleFiles(files);
    }

    var fileList;
    var totalFileSize;

    /* Allows for changing all select types to one type */
    $('select#MediaPoolType').bind('change', function() {
        var value = $(this).val();

        $('ul.mediaList select').val(value);
    });

    $('input#MediaPoolComment').keyup(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
        
        var value = $(this).val();

        if (value.length > 2) {
            $('ul.mediaList input.mediaComment').val(value);
        }
    });

    $('#MediaPoolName').bind('change', function() {
        fileList = $(this)[0].files;
        handleFiles(fileList);
    });

    function handleFiles(files) {
        totalFileSize = 0;
        $('ul.mediaList').empty();

        $(files).each(function(e,k) {
            var image = $('<img />').attr('class', 'previewImage');
            image.attr('width', '200px');
            image.attr('height', '150px');

            totalFileSize += parseInt(k.size);
            var reader = new FileReader();
            reader.onload = (function(aImg) {
                return function(e) {
                    $(aImg).attr('src', e.target.result);
                };
            })(image);

            // Read the file entry.
            reader.readAsDataURL(k);

            var e = $('<li><strong>Filename</strong><br />' + k.name + '<div class="imagePreview"></div><strong>Image Type</strong><br /><div class="imageType"></div>' +
                    '<div class="imageComment"><strong>Comment</strong><br /><input type="text" class="mediaComment" name="data[mediacomment][]" size="35" value="N/A" /></li>');
            $(e).find('div.imagePreview').append(image);

            var mediaSelect = $('select#MediaPoolType').clone();
            mediaSelect.attr('id', '');
            mediaSelect.attr('name', 'data[mediatypes][]');
            $(e).find('div.imageType:first').append(mediaSelect);

            $('ul.mediaList').append(e);
        });
        totalFileSizeKb = Math.round((totalFileSize / 1000) * 100) / 100;
        $('span#totalFileSize span').text(totalFileSizeKb);
        $('span#mediaCounter span').text($(fileList).size());
    }

    // Enable larger preview.
    $('ul.mediaList').on('click', 'img.previewImage', function() {
        var imageWidth = $(this).attr('width');

        if (imageWidth == 300) {
            $(this).attr('width', 200);
        } else {
            $(this).attr('width', 300);
        }
    });
});

</script>
<h1>Add media</h1>
Files uploaded from here will be added to the pool of available resources and can used in reviews and game entries
for others. 

<p><strong>Note:</strong> Please consider adding a useful comment to each media file. It allows you and others to easier find them later on.</p>

<?php
if (isset($errors)) {
    echo '<div class="error message">';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

$fileTypes = array(
    'screenshot' => 'Screenshot',
    'gamefile' => 'Game File (something you can download)',
    'audio' => 'Audio/sound file',
    'manual' => 'Manual',
    'video' => 'Video',
    'walkthrough' => 'Walkthrough',
    'front_cover' => 'Front Cover Media',
    'back_cover' => 'Back Cover Media',
    'illustration' => 'Game illustration (Cover, cds, manual, etc.)',
    'advertisement' => 'Advertisement'
);

echo $this->Form->create('MediaPool', array('type' => 'file', 'method' => 'post'));
?>

<div id="media">
    <?php echo '<h4>Select files (you can select multiple)</h4>' . $this->Form->file('name', array('multiple' => 'multiple', 'accept' => 'image/*', 'size' => '90', 'name' => 'data[media][]')); ?>
</div>

<h3>These media files have been marked for upload</h3>

Change all media types to: 
<?php
    echo $this->Form->select('MediaPool.type', $fileTypes, array('empty' => false, 'name' => ''));
?>

Give all selected media a comment:
<?php
    echo $this->Form->text('MediaPool.comment', array('name' => ''));
?>

<div>
    <span class="button" id="mediaCounter">Media count: <span>0</span></span> <span class="button" id="totalFileSize">Total upload size <span>0</span> Kb.</span>
</div>

<ul class="mediaList"></ul>

<?php
    echo $this->Form->end('Add');
?>