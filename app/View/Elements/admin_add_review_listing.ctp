<?php
echo '<h2> ' . __('Add review for games') . '</h2>';
?>
These are games that doesn't yet have a review. You can add one by clicking the games below!
<ul>
    <li>Green are published and can be accessed. </li>
    <li>Orange are awaiting publishing and cannot be accessed publicly.</li>
    <li>Red are inactive and cannot be accessed publicly.</li>
</ul>

<?php
    echo '<span class="button">' . $this->Paginator->sort('Game.publish_date', __('Order by publish date'), array('direction' => 'desc')) . '</span>';
    echo '<span class="button">' . $this->Paginator->sort('Game.active', __('Order by active entries'), array('direction' => 'asc')) . '</span>';
?>

<ul id="reviewList" class="cleanList cleanCardList roundedCorner ">
<?php
    foreach ($data as $entry) {
        $publishDate = date('d.m Y', strtotime($entry['Game']['publish_date']));
        $active = $entry['Game']['active'];
        if (date('Y-m-d H:i:s', time()) > $entry['Game']['publish_date']) {
            if ($active) {
                $background = 'green';
                $publishStatusTitle = __('%s was published on %s. It is active and can be accessed.', array($entry['Game']['name'], $publishDate));
            } else {
                $background = 'red';
                $publishStatusTitle = __('%s has been set to inactive and can not be accessed by the public. You can preview it though.', array($entry['Game']['name']));
            }
        } else {
            $publishStatusTitle = __('%s will be published %s', array($entry['Game']['name'], $publishDate));
            $background = 'orange';
        }

        echo '
        <li class="defaultShadow" title="' . __('Add review for %s', array($entry['Game']['name'])) . '">
        <div class="itemStatus ' . $background  . '"></div>
        <a href="/admin/add/review/' . $entry['Game']['slug'] . '/' . $entry['Game']['id'] . '">
            <h3><img src="/images/icon/pencil-plus.png" hspace="5" alt="pencil icon" />' . $entry['Game']['name'] . '</h3>
            <img src="/image/view/type:focus/' . $entry['Game']['focus'] . '/320/nowatermark/cache" alt="fokus" />
            <p>This game has no review. Click to add one for this game</p>
        </a>
        <p>' . $publishStatusTitle . '</p>
        
        </li>';
    }
    ?>
</ul>

<?php echo $this->element('default_pagination_markup');