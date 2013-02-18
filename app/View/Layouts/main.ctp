<?php
echo $this->Html->docType();
?>
<head>
<?php
echo $this->Html->charset();
echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
echo $this->Html->css(
    array(
    'layout',
    'navigation',
    'interface',
    'formatting',
    'common',
    'slimbox',
    'messages',
    'featured',
    'news',
    'autocomplete'
    )
);

echo $this->Html->script(array(
	'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js',
	'jquery.slide',
	'jquery.autocomplete.min')
);

?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="index, follow">

<title><?php echo $title_for_layout ?> - The DOS Spirit, since 1832 &trade;</title>
<meta name="description" content="The DOS Spirit er Norges eneste seri&oslash;se nettsted som fokuserer p&aring; de gode gamle dagene i spillindustrien for DOS-spill vi enten kjenner til eller ei, hater og/eller elsker." />
<meta name="rating" content="General" />
<meta name="Author" content="The DOS Spirit Foundation" />
<meta name="copyright" content="Copyright &copy; dosspirit.net and The DOS Spirit Foundation(&copy;) 2004-<?php echo date('Y', time()); ?>" />
<meta name="distribution" content="global" />
<?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
?>
</head>
<body>
<?php echo $this->element('site_top'); ?>
<div id="page">
<?php echo $this->element('header'); ?>
<?php
echo $this->element('main_navigation');
echo $this->Session->flash();
?>
<div class="content">
    <?php echo $this->fetch('content'); ?>
    <div class="clearer"></div>
</div>
<?php echo $this->element('footer'); ?>
</div>
</body>
</html>