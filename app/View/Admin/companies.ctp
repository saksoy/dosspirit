<h1>Companies</h1>

<span class="button"><a href="/admin/add/company">Add new company</a></span>
<ul class="cleanList cleanCardList">
<?php 
foreach ($companies as $company) {
    echo '<li><a href="/admin/edit/company/' . $company['Company']['slug'] . '/' . $company['Company']['id'] . '">' . $company['Company']['name'] . '</li>';
}
?>
</ul>