<?php
function tcmp_ui_admin_options() {
    global $tcmp;

    ?>
    <div style="float:left; min-width:750px">

    <?php

    $tcmp->Form->prefix = 'AdminOptions';
    $tcmp->Form->formStarts();

    if ($tcmp->Check->nonce('tcmp_admin_options')) {
        $tcmp->Options->setModifySuperglobalVariable($tcmp->Utils->iqs('checkbox'));
    }

    $tcmp->Form->p(__('Enable option to change cache behavior'));
    
    $modify = $tcmp->Options->getModifySuperglobalVariable();
    
    $tcmp->Form->checkbox('checkbox', $modify);
    $tcmp->Form->p('NOTE: From time to time, Support may recommend the superglobal switch to be turned on. Please do not turn it on unless support gives you direction to do so.');

    $tcmp->Form->nonce('tcmp_admin_options');
    $tcmp->Form->br();
    $tcmp->Form->submit('Save');
    $tcmp->Form->formEnds();

    ?> </div> <?php
}