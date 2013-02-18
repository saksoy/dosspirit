<h3> <?php echo __('Reviews you have written'); ?></h3>
<?php
    echo '<span class="button">' . $this->Paginator->sort('Review.publish_date', __('Order by publish date'), array('direction' => 'desc')) . '</span>';
    echo '<span class="button">' . $this->Paginator->sort('Review.draft', __('Order by draft entries'), array('direction' => 'desc')) . '</span>';
?>

<p>Click an entry to edit</p>

<ul id="reviewList" class="cleanList cleanCardList roundedCorner ">

<?php
    foreach ($data as $entry) {
        $publishDate = date('d.m Y H:i', strtotime($entry['Review']['publish_date']));
        if (date('Y-m-d', time()) > $entry['Review']['publish_date']) {
            if ($entry['Review']['draft'] == 1) {
                $background = 'red';
                $publishStatusTitle = __('This review was published %s, but will not be seen because it is in draft.', array($publishDate));
            } else {
                $background = 'green';
                $publishStatusTitle = __('This review was published on %s', array($publishDate));
            }
        } else {
            $publishStatus = 'hourglass.png';
            if ($entry['Review']['draft'] == 1) {
                $background = 'red';
                $publishStatusTitle = __('This review is in draft and will not be shown until you publish it.');
            } else {
                $publishStatusTitle = __('This review will be published %s', array($publishDate));
                $background = 'orange';
            }
        }

        echo '
        <li class="defaultShadow" title="Click edit review of ' . $entry['Game']['name'] . '">
        <div class="itemStatus ' . $background  . '"></div>
        <a href="/admin/edit/review/' . $entry['Game']['slug'] . '/' . $entry['Game']['id'] . '">
            <h3><img src="/images/icon/pencil.png" hspace="5" alt="pencil icon" />' . $entry['Game']['name'] . '</h3>
            <img src="/image/view/type:focus/' . $entry['Game']['focus'] . '/320/nowatermark/cache" alt="fokus" />
        </a>
        <p>' . $publishStatusTitle . '</p>
        <a href="/game/preview/' . $entry['Game']['slug'] . '/' . $entry['Game']['id'] . '">Preview this entry</a>
        </li>';
    }
    ?>
</ul>

<?php echo $this->element('default_pagination_markup'); ?>