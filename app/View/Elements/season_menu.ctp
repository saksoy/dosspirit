<menu class="cleanList horizontalNavigation seasonMenu">
<?php 

$thisYear = date('Y', time());
    foreach ($seasons as $seasonName) {
        echo '<li class="subMenuListItem"><a href="#">' . ucfirst($seasonName) . '</a>
        <ul class="subMenu">';
        for ($year = 2005; $year <= $thisYear; $year++) {
            echo '<li><a href="/season/' . $seasonName . '/' . $year . '">' . ucfirst($seasonName) . ' ' . $year . '</a></li>';
        }
        
        echo '</ul>
        </li>';
    }
    
?>
</menu>