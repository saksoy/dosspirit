<?php echo $this->Html->docType(); ?>
<html>
<head>
<?php
echo $this->Html->charset('utf-8');
echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));
echo $this->Html->css(
array(
    'layout',
    'navigation',
    'interface',
    'formatting',
    'common',
    'slimbox',
    'review',
    'messages',
    'theme/christmas',
    'autocomplete'
)
);

echo $this->Html->script(array(
	'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
	'slimbox2',
	'fla',
	'jquery.autocomplete.min',
	'jquery.slide')
);

?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="index, follow" />
<meta property="fb:app_id" content="115077385194164" />
<meta property="fb:admins" content="625942115" />

<title><?php echo $title_for_layout ?> - The DOS Spirit, since 1832 &trade;</title>
<meta name="description" content="The DOS Spirit er Norges eneste seri&oslash;se nettsted som fokuserer p&aring; de gode gamle dagene i spillindustrien for DOS-spill vi enten kjenner til eller ei, hater og/eller elsker." />
<meta name="rating" content="General" />
<meta name="Author" content="The DOS Spirit Foundation" />
<meta name="copyright" content="Copyright &copy; dosspirit.net and The DOS Spirit Foundation(&copy;) 2001-2011" />
<meta name="distribution" content="global" />
<?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
?>
</head>
<body>
<?php
    echo $this->element('site_top');
?>

<div id="page">
    <?php
    echo $this->element('header');
    echo $this->element('main_navigation');
    ?>

    <div class="content">
    <?php
        echo $this->element('google_adsense', array('type' => 'horizontal', 'height' => 90, 'width' => 728));
        echo $this->Session->flash();
        echo $this->fetch('content');
    ?>
    </div>

    <div class="clearer"></div>
    <?php echo $this->element('footer'); ?>
</div>
</body>
</html>