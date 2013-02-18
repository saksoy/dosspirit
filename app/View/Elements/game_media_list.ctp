<?php
if (!empty($media)) {
    foreach ($media as $mediaKey => $mediaEntries) {
        if ($mediaKey !== 'gamefile') {
            echo '<section class="' . $mediaKey . '">';
            echo '<h4>' . ucfirst($mediaKey) . '(s) (' . count($mediaEntries) . ')</h4>';
            foreach ($mediaEntries as $m) {
                $m['comment'] = str_replace('"', '', $m['comment']);
                echo $this->Html->link(
                        $this->Html->image('/image/view/' . $m['filename'] . '/150/no-watermark/cache',
                                array(
                                        'title' => $m['comment'] . ' - ' . $m['created'],
                                        'alt' => $m['comment'])
                        ),
                        '/image/view/' . $m['filename'] . '/640/watermark/cache',
                        array(
                                'escape' => false,
                                'rel' => 'lightbox-game-images',
                                'title' => $m['comment'] . ' - <em> ' . $m['created'] . '</em>',
                                'alt' => $m['comment'])
                );
            }
            echo '</section>';
        }
    }
} else {
    echo __('No media added yet. Why not help add some?');
}