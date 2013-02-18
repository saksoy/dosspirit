<div class="network">
    <div class="center-wrapper">
        <menu class="horizontalNavigation">
            <li><a href="http://www.multigamer.no/about/cn">CN-sites &raquo;</a></li>
            <li><a href="http://www.multigamer.no">Multigamer.no</a></li>
            <li><a href="http://www.battlefield.no">Battlefield.no</a></li>
            <li><a href="http://www.fmnorge.com">FM Norge</a></li>
            <li><a href="http://www.halonorge.org">HaloNorge.org</a></li>
            <li><a href="http://www.hellfragger.no">Hellfragger.no</a></li>
            <li><a href="#" title="Check out our Retro Radio! Click to tune in! (POP-up)" onClick="retroPlayer();"><img src="/images/icons/radio.gif" hspace="4" border="0" style="margin-top: -8px;" align="right">Retro Radio! (517) songs!</a></li>
            <?php echo $this->element('language_select'); ?>
        </menu>
    </div>
</div>

<script type="text/javascript">
function retroPlayer() {
	newwindow=window.open('http://dosspirit.net/radio','The DOS Spirit Retro Radio!','height=800,width=420,resizeable=1,menubar=0,scrollbars=1');
	if (window.focus) {newwindow.focus()}
	return false;

}
</script>