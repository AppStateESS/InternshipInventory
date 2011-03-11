<?php

/**
 * Sysinventory_Util
 *
 * Couldn't find anywhere else to push this reroute function.
 * We might find some other random functions to place in here too.
 *
 * @author Robert Bost <bostrt at appstate dot edu>
 */
class Sysinventory_Util 
{
    /**
     * Reroute to the passed address. Close NQ before we reroute.
     */
    public static function reroute($address=NULL)
    {
        NQ::close();
        PHPWS_Core::reroute($address);
    }
}

?>