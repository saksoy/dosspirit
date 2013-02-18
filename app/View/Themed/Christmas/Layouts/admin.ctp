<?php echo $this->Html->docType(); ?>
<html>
<head>
<?php
echo $this->Html->charset('utf-8');
echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
echo $this->Html->css(
    array(
    'layout',
    'interface',
    'formatting',
    'admin',
    'slimbox',
    'menu',
    'messages',
    'common',
    'navigation',
    'theme/christmas',
    'markitup-bbcode',
    'markitup-style',
    'autocomplete'
    )
);

echo $this->Html->script(array(
	'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js',
	'jquery.markitup.bbcode.set',
	'jquery.markitup',
	'slimbox2',
	'jquery.autocomplete.min')
);
echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php echo $title_for_layout ?> - The DOS Spirit, since 1832 &trade;</title>
<meta name="description" content="The DOS Spirit er Norges eneste seri&oslash;se nettsted som fokuserer p&aring; de gode gamle dagene i spillindustrien for DOS-spill vi enten kjenner til eller ei, hater og/eller elsker.">
<meta name="rating" content="General">
<meta name="Author" content="The DOS Spirit Foundation">
<meta name="copyright" content="Copyright dosspirit.net, The DOS Spirit Foundation(&copy;) and Multigamer by Norsk eSport 2004-<?php echo date('Y', time()); ?>">
<meta name="distribution" content="global">

<?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
?>
</head>
<body>
<?php echo $this->element('site_top'); ?>
<div id="page">
    <?php
    echo $this->element('header');
    echo $this->element('main_navigation'); ?>

	<div id="content">
    <?php
        if (!empty($user)) {
            $userStatus = ($user['admin'] == 1) ? 'Admin' : 'Regular user';
        	echo '<h4>Welcome, ' . $user['username'] . '!  (' . __('Level') . ': ' . $this->CommonViewFunctions->experienceLevel($user['experience']) . ' - ' . $user['experience'] . ' points. ' . __('Experience to next level') . ': ' . $this->CommonViewFunctions->experienceToNextLevel($user['experience']) . ') <img src="/images/avatar/' . $user['avatar'] . '" width="40" alt="user avatar" title="Avatar" style="float: right;" /></h4>
        	User status: ' . $userStatus . '
        	<menu class="horizontalNavigation">
            	<li>' . $this->Html->link(__('Dashboard'), array('controller' => 'admin', 'action' => 'dashboard')) . '</li>
            	<li>' . $this->Html->link(__('Log out'), array('controller' => 'admin', 'action' => 'logout')) . '</li>
            	<li><a href="/admin/inbox"><img src="/images/icon/inbox.png" alt="inbox" />Inbox (' . $userInboxCount . ')</a></li>
        	</menu>';
        }
        echo $this->Session->flash();
        echo $this->Session->flash('auth');
        echo $this->Session->flash('email');

        echo $this->fetch('content'); ?>
        <div class="clearer"></div>
    </div>

    <?php echo $this->element('footer'); ?>
</div>
</body>
</html>