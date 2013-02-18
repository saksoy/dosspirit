<?php
switch ($listType) {
    case 'gameListing':
        echo $this->element('admin_game_listing', array('data' => $data));
        break;
    case 'reviewListingAdd':
        echo $this->element('admin_add_review_listing', array('data' => $data));
        break;
    case 'reviewListingEdit':
        echo $this->element('admin_review_listing', array('data' => $data));
        break;
}
?>