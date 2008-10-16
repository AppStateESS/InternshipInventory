<?php

PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Default.php');

$default['json_data'] = Sysinventory_Default::getJSON(); 

?>
