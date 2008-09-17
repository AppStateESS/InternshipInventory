<?php
/*
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 */
function sysinventory_uninstall(&$content) {

    PHPWS_DB::dropTable('sysinventory_location');
    PHPWS_DB::dropTable('sysinventory_department');
    PHPWS_DB::dropTable('sysinventory_office');
    PHPWS_DB::dropTable('sysinventory_computer');
    PHPWS_DB::dropTable('sysinventory_printer');
    PHPWS_DB::dropTable('sysinventory_employee');
    $content[] = dgettext('skeleton', 'Skeleton tables dropped.');

    return true;
}
?>
