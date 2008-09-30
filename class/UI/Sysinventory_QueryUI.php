<?php
/**
 * Class for making the query UI
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */

 class Sysinventory_QueryUI {
    var $departments = NULL;

    function display() {

        // extra page tags
        $tpl['HOMELINK'] = PHPWS_Text::moduleLink('Back to Menu','sysinventory');
        
        // form for building a query
        $form = new PHPWS_Form('query');
        
    }
 }
?>
