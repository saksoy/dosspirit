
<h1><?php echo $profile['User']['username']; ?></h1>
<img src="/images/avatar/<?php echo $profile['User']['avatar']; ?>" alt="avatar" />

<ul class="verticalNavigation">
    <li>Experience: <?php echo $profile['User']['experience']; ?></li>
    <li>Joined: <?php echo $profile['User']['created'] . ' (' . $this->CommonViewFunctions->memberDuration($profile['User']['created']) . ')'; ?>
</ul>
<div class="contentColumn">
<h3><?php echo __('Recent games added by this user'); ?></h3>
<?php
echo $this->element('user_game_listing', array('gameList' => $profile['Game']));
?>
</div>
<div class="contentColumn">


<h3><?php echo __('Recent reviews added by this user'); ?></h3>
<?php
echo $this->element('user_review_listing', array('reviewList' => $profile['Review']));
?>


<h3><?php echo __('Recent news articles added by this user'); ?></h3>
<?php 
echo $this->element('user_news_listing', array('newsList' => $profile['NewsArticle']));
?>
</div>
