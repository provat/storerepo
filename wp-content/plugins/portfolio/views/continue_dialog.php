<?php

/**
 * Markup needed for jQuery UI dialog, our form is actually loaded via AJAX
 */
?>
<div id="continu_project_dialog" class="continu_project_dialog" title="Update Project" data-security="<?php print wp_create_nonce( 'continu_project_dialog' ); ?>">
    <div id="continu_project_dialog_target"><span class="loader_img"></div></div>
</div>
<div class="clear"></div>