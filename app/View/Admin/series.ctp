<h1>Game Series</h1>

<span class="button"><a href="/admin/add/serie">Add new serie</a></span>
<ul class="cleanList cleanCardList">
<?php 
foreach ($series as $serie) {
    echo '<li><a href="/admin/edit/serie/' . $serie['Serie']['slug'] . '/' . $serie['Serie']['id'] . '">' . $serie['Serie']['name'] . '</li>';
}
?>
</ul>