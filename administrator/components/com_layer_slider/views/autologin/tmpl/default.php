<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
?><?php
defined('_JEXEC') or die;
?>
<script>
clearInterval(window.opener.backendTmr);
window.opener.backendWnd = 0;
window.opener.jQuery('.ls-publish').removeClass('saved').find('button').removeAttr('disabled').click();
window.close();
</script>