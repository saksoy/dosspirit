<ul class="cleanList">


<?php
if (count($reviewList) > 0) {
    foreach ($reviewList as $entry) {
        echo '<h5>' . $entry['Game']['name'] .' (' . $entry['Game']['year'] . ') </h5>
        <a href="/' . $selectedLanguage . '/game/' . strtolower($entry['Game']['slug']) . '/' . $entry['Game']['id'] . '">
        <img src="/image/view/type:focus/' . $entry['Game']['focus'] . '/120" alt="' . __('Focus image of %s', array($entry['Game']['name'])) .'" border="0" title="' . $entry['Game']['name'] . '" />
        </a>' .
        $entry['Review']['teaser_text'];
    }
} else {
    echo '<li>' . __('This user has not added any reviews yet.') . '</li>';
}
?>

</ul>
