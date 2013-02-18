<?php echo $this->Html->docType(); ?>
<html>
<head>
<?php
echo $this->Html->charset('utf-8');
echo $this->Html->meta('favicon.ico',
'/favicon.ico',
array('type' => 'icon')
);
echo $this->Html->css(
    array(
    'layout',
    'interface',
    'formatting',
    'navigation'
    )
);

echo $this->Html->script('fla');

echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Retro Radio - The DOS Spirit, since 1832 &trade;</title>
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

<!-- Start of StatCounter Code -->
<script type="text/javascript">
var sc_project=4630340;
var sc_invisible=0;
var sc_partition=56;
var sc_click_stat=1;
var sc_security="619987c6";
var sc_text=2;
</script>
</head>
<body>
<div id="radioPage">
    <a href="/"><img src="/images/logo.png" alt="logo" title="The DOS Spirit" /></a>
	<div id="content">
        <?php echo $this->fetch('content'); ?>
    </div>
</div>
</body>
</html>