<div class="dashBoard">
<?php
echo $this->Session->flash();
?>
<div id="tipContainer" class="message"><?php echo __('Hover over links to get tooltips displayed here.'); ?></div>

<h1>My Actions</h1>
<ul class="cleanList verticalNavigation">
<li title="<?php echo __('Add new game entry'); ?>"><img src="/images/icon/plus.png" alt="add game" /><a href="/admin/add/game">Add game</a></li>
<li title="<?php echo __('Edit an existing game entry'); ?>"><img src="/images/icon/pencil.png" alt="edit game" /><a href="/admin/edit/game">Edit game</a></li>
<li title="<?php echo __('Add various media like screenshots that can be used by anyone when adding games'); ?>"><img src="/images/icon/save.png" alt="add media" /><a href="/admin/add/media/">Add Media</a></li>
<li title="<?php echo __('Edit my profile'); ?>"><img src="/images/icon/user.png" alt="edit profile" /><a href="/admin/profile">Edit my profile</a></li>
<li title="<?php echo __('Add your own user review of a game from the database'); ?>"><img src="/images/icon/plus.png" alt="add game review" /><a href="/admin/add/userreview">Add my own review</a></li>
</ul>

<?php
if (isset($user) && $user['admin'] == 1) {
?>
<h1>Admin Actions</h1>
<div class="columnLeft">
    <h2>Game</h2>
    <ul class="cleanList verticalNavigation">
    	<li title="<?php echo __('Add new game entry'); ?>"><img src="/images/icon/plus.png" alt="add game" /><a href="/admin/add/game">Add game</a></li>
        <li title="<?php echo __('Edit an existing game entry'); ?>"><img src="/images/icon/pencil.png" alt="edit game" /><a href="/admin/edit/game">Edit game</a></li>
        <li title="<?php echo __('Add new game serie'); ?>"><img src="/images/icon/plus.png" alt="add game serie" /><a href="/admin/add/serie">Add game serie</a></li>
    </ul>
    <h2>Review</h2>
    <ul class="cleanList verticalNavigation">
        <li title="<?php echo __('Add new review for an existing game'); ?>"><img src="/images/icon/plus.png" alt="add review" /><a href="/admin/add/review">Add review</a></li>
        <li title="<?php echo __('Edit an existing review'); ?>"><img src="/images/icon/pencil.png" alt="edit review" /><a href="/admin/edit/review">Edit review</a></li>
    </ul>
    <h2>News</h2>
    <ul class="cleanList verticalNavigation">
        <li title="<?php echo __('Add new news story'); ?>"><img src="/images/icon/pencil.png" alt="add news" /><a href="/admin/add/news">Add news</a></li>
        <li title="<?php echo __('Edit existing news story'); ?>"><img src="/images/icon/edit.png" alt="edit news" /><a href="/admin/edit/news">Edit news</a></li>
    </ul>
</div>
<div class="columnRight">
    <h2>Site control</h2>
    <ul class="cleanList verticalNavigation">
    	<li title="<?php echo __('Add new poll'); ?>"><img src="/images/icon/chart.png" alt="add poll" /><a href="/admin/add/poll">Add poll</a></li>
    	<li title="<?php echo __('Site statistics breakdown'); ?>"><img src="/images/icon/statistics.png" alt="site stats" /><a href="/admin/site/stats">Site Stats</a></li>
        <li title="<?php echo __('Configure things like editors choice on the frontpage, amogst other things'); ?>"><img src="/images/icon/tool.png" alt="edit site settings" /><a href="/admin/sitesettings">Site settings</a></li>
        <li title="<?php echo __('Add new companies (developers, publishers etc.) that can be used when adding or editing games'); ?>"><img src="/images/icon/user.png" alt="manage registrations" /><a href="/admin/companies">Manage companies</a></li>
		<li title="<?php echo __('If a change requires you to see something immediately, use this to clear the cache. Be patient as it can take a while.'); ?>"><img src="/images/icon/exclamation.png" alt="clear cache" /><a href="/admin/dashboard/purgecache">Clear cache</a></li>
    </ul>
    <h2>Manage User Generated Content</h2>
    <ul class="cleanList verticalNavigation">
        <li title="<?php echo __('Administrate uploaded media'); ?>"><img src="/images/icon/save.png" alt="admin media" /><a href="/admin/media">Admin media</a></li>
        <li title="<?php echo __('Overview of user accounts, who has activated their account and more'); ?>"><img src="/images/icon/user.png" alt="user accounts" /><a href="/admin/accounts">User accounts</a></li>
        <li title="<?php echo __('Handle approval of user submitted reviews'); ?>"><img src="/images/icon/user.png" alt="manage game review" /><a href="/admin/accounts">Manage game review</a></li>
        <li title="<?php echo __('Manage videos like uploading or attaching existing files to game entries'); ?>"><img src="/images/icon/video.png" alt="manage videos" /><a href="/admin/accounts">Manage videos</a></li>
    </ul>
</div>
<?php } ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('li').hover(function() {
        $('div#tipContainer').text($(this).attr('title'));
    });
});
</script>