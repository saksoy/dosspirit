<h2><?php echo __('Recent games you and others have added'); ?></h2>
<ul>
    <li>Green are published and can be accessed. </li>
    <li>Orange are awaiting publishing and cannot be accessed publicly.</li>
    <li>Red are inactive and cannot be accessed publicly.</li>
</ul>

<?php
    echo '<span class="button">' . $this->Paginator->sort('Game.publish_date', __('Order by publish date'), array('direction' => 'desc')) . '</span>';
    echo '<span class="button">' . $this->Paginator->sort('Game.active', __('Order by active entries'), array('direction' => 'asc')) . '</span>';
?>
<ul id="gameList" class="cleanList cleanCardList roundedCorner">
<?php
foreach ($data as $entry) {
    $published = 0;
    $active = 0;

    if (date('Y-m-d H:i:s', time()) > $entry['Game']['publish_date']) {
        $published = 1;
        $publishStatus = 'ok.png';
        $publishStatusTitle = __('Published on %s.', array($entry['Game']['publish_date']));
    } else {
        $publishStatus = 'hourglass.png';
        $publishStatusTitle = __('Will be published %s. You can preview it in the mean time.', array($entry['Game']['publish_date']));
    }

    if ($entry['Game']['active'] == 1) {
        $active = 1;
        $activeStatus = 'ok.png';
        $activeStatusTitle = __('%s is active.', array($entry['Game']['name']));
    } else {
        $activeStatus = 'cross.png';
        $activeStatusTitle = __('%s has been set to inactive and can not be accessed by the public. You can preview it though.', array($entry['Game']['name']));
    }

    if ($published && $active) {
        $overallStatus = 'ok.png';
        $overallStatusTitle = __('%s is active and can be accessed.', array($entry['Game']['name']));
        $background = 'green';
    } else if (!$active) {
        $overallStatus = $activeStatus;
        $overallStatusTitle = $activeStatusTitle;
        $background = 'red';
    } else if (!$published) {
        $overallStatus = $publishStatus;
        $overallStatusTitle = $publishStatusTitle;
        $background = 'orange';
    }

    if ($user['id'] == $entry['Game']['user_id']) {
        $whoAddedTheEntry = __('You added this game entry.');
    } else {
        $whoAddedTheEntry = __('Someone else added this game entry.');
    }

    echo '<li class="defaultShadow" title="' .  __('Edit %s', array($entry['Game']['name'])) . '">
    <div class="itemStatus ' . $background  . '"></div>
    <a href="/admin/edit/game/' . $entry['Game']['slug'] . '/' . $entry['Game']['id'] . '">
    	<h3><img src="/images/icon/pencil.png" hspace="5" alt="pencil icon" />' . $entry['Game']['name'] . '</h3>
    	' . $whoAddedTheEntry . '
		<img src="/image/view/type:focus/' . $entry['Game']['focus'] . '/320/nowatermark/cache" alt="fokus" />
    </a>
    <p>' . $publishStatusTitle . '</p>
    <a href="/game/preview/' . $entry['Game']['slug'] . '/' . $entry['Game']['id'] . '"><img src="/images/icon/preview.png" hspace="5" alt="preview icon" />' . __('Preview %s', array($entry['Game']['name'])) . '</a>
    <p><img src="/images/icon/' . $publishStatus . '" title="' . $publishStatusTitle . '" /> ' . $publishStatusTitle . '</p>
    <p><img src="/images/icon/' . $activeStatus . '" title="' . $activeStatusTitle . '" /> ' . $activeStatusTitle . '</p>
    </li>';
}
?>
</ul>

<?php echo $this->element('default_pagination_markup'); ?>