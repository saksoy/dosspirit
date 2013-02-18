<ul class="inboxList verticalNavigation">
<?php
foreach ($inbox as $entry) {
    $log = $entry['UserInbox'];

    switch ($log['type']) {
        case 'added media':
            $contextHeader = __('You added a media file');
            $context = '<a href="/images/mediapool/' . $log['link'] . '" rel="lightbox-inbox-image"><img src="/image/view/type:pool/' . $log['link'] . '/200/no-watermark/cache" alt="image" /></a>';
            break;
        case 'added review':
            $contextHeader = __('You added a review');
            $context = $log['link'];
            break;
        case 'added video':
            $contextHeader = __('You added a video');
            $context = $log['link'];
            break;
        case 'accepted media':
            $contextHeader = __('You accepted a media file');
            $context = '<a href="/images/mediapool/' . $log['link'] . '" rel="lightbox-inbox-image"><img src="/image/view/type:pool/' . $log['link'] . '/200/no-watermark/cache" alt="image" /></a>';
            break;
        case 'media accepted':
            $contextHeader = __('Your media file was accepted');
            $context = '<a href="/images/mediapool/' . $log['link'] . '" rel="lightbox-inbox-image"><img src="/image/view/type:pool/' . $log['link'] . '/200/no-watermark/cache" alt="image" /></a>';
            break;
        case 'added user review':
            $contextHeader = __('You added a user review');
            $context = $log['link'];
            break;
        case 'accepted user review':
            $contextHeader = __('You accepted a user review');
            $context = $log['link'];
            break;
        case 'rejected media':
            $contextHeader = __('Your media file was rejected');
            $context = $log['link'];
            break;
        default:
            $context = '';
            $contextHeader = '';
        break;
    }

    echo '<li>
    	<span class="detailBox" style="float:right;">' . $log['created'] .
    	'<p>' . __('Experience rewarded %s points.', array($log['reward'])) . '</p>
    	</span>
    	<h5>' . $contextHeader . '</h5>
        <p>' . $log['activity'] . '</p>
        <p>' . $context . '</p>

        <p>
            <span class="button">
            	<img src="/images/icon/cross.png" alt="remove" rel="' . $log['id'] . '" /> Delete message
            </span>
        </p>
    </li>';
}
?>
</ul>