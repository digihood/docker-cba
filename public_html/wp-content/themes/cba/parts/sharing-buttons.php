<?php 
$link = get_permalink();

if ( $link ) { 
		
?>
<div class="social-share">
    <h3><?= __('Sdílejte s ostatními', 'cba'); ?></h3>
    <div class="social-share-wrap">
        <a href="http://www.facebook.com/sharer/sharer.php?u='. $link .'" onclick="window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes,  width=600,  height=400'); return false;" target="_blank" rel="nofollow"><?= d1g1B::icon('facebook'); ?></a>
        <a href="https://twitter.com/intent/tweet?text=<?=$link?>" onclick="window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes,  width=600,  height=400'); return false;" target="_blank" rel="nofollow"><?php d1g1B::icon('twitter'); ?></a>
        <a href="fb-messenger://share/?link=<?=$link?>"><?php d1g1B::icon('mess'); ?></a>
        <a href="https://api.whatsapp.com/send&text=<?=$link?>" data-action="share/whatsapp/share" target="_blank"><?php d1g1B::icon('whatsapp'); ?></a>
        <a href="<?php echo 'mailto:?subject='.__('Zajímavý odkaz', 'cba').'&body='.__('Ahoj, sdílím odkaz s tebou odkaz ', 'cba').''. $link; ?>"><?php d1g1B::icon('mail'); ?></a>
    </div>
</div>
<?php } ?>