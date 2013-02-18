<nav class="mainNavigation horizontalNavigation">
    <ul>
        <li><?php echo $this->element('search_box'); ?></li>
        <li><?php echo '<a href="/' . $selectedLanguage .'">' . __('Home') . '</a>'; ?></li>
        <li><?php echo $this->Html->link(__('News'), array('controller' => 'news', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('Games'), array('controller' => 'game', 'action' => 'all')); ?></li>
        <li><?php echo $this->Html->link(__('Random game'), array('controller' => 'game', 'action' => 'random')); ?></li>
        <li><?php echo $this->Html->link(__('Search'), array('controller' => 'search', 'action' => 'search')); ?></li>
        <li class="subMenuListItem"><a href="#">Season</a>
            <ul class="subMenu">
            <?php
            echo $this->element('season_menu', array('seasons' => $validSeasons));
            ?>
            </ul>
        </li>
        <li><?php echo $this->Html->link(__('Articles'), array('controller' => 'articles', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('Contact'), array('controller' => 'contact', 'action' => 'index')); ?></li>



        <?php
        if (isset($user)) {
            echo '<li class="subMenuListItem" id="userSubMenu" title="You have ' . $userInboxCount . ' unread messages in your inbox"><a href="/admin/dashboard"><img src="/image/view/type:avatar/' . $user['avatar'] . '/20/no-watermark/cache" />' . $user['username'] . ' (' . $userInboxCount . ')</a>
            	<ul class="subMenu" id="userActionList">
            		<li><a href="/admin/dashboard">' . __('Dashboard') . '</a></li>
            		<li><a href="/admin/inbox">' . __('Inbox (%s)', array($userInboxCount)) . '</a></li>
            		<li><a href="/admin/profile">' . __('My profile') . '</a></li>
            		<li><a href="/admin/profile#achievements">' . __('My achievements') . '</a></li>
            		<li><a href="/admin/add/game">' . __('Add game') . '</a></li>
            		<li><a href="/admin/add/media">' . __('Add media') . '</a></li>
            		<li><a href="/admin/add/review">' . __('Add review') . '</a></li>
            		<li><a href="/admin/edit/game">' . __('Edit game') . '</a></li>
            		<li><a href="/admin/edit/review">' . __('Edit review') . '</a></li>
            		<li><a href="/admin/logout">' . __('Logout') . '</a></li>
        		</ul>
            </li>';
        } else {
            echo '<li><a href="/login" title="Admin">' . __('Login') . '</a></li>';
        }
        ?>

        <li><a href="http://www.multigamer.no/forum/" title="Forum" target="_blank"><?php echo __('Forum'); ?></a></li>
    </ul>
</nav>