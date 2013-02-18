<ul class="cleanList">


<?php
if (count($gameList) > 0) {
    foreach ($gameList as $game) {
        echo '<h5>' . $game['name'] .' (' . $game['year'] . ') </h5>
        <a href="/game/' . strtolower($game['slug']) . '/' . $game['id'] . '">
        <img src="/image/view/type:focus/' . $game['focus'] . '/120" title="' . __('Focus image of %s', array($game['name'])) .'" border="0" alt="Focus image of ' . $game['name'] . '" />
        </a>
        ';
    }
} else {
    echo '<li>' . __('This user has not added any games yet.') . '</li>';
}
?>

</ul>
