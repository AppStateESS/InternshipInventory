<?php
namespace Intern\Command;

/**
 *
 * @author Olivia Perugini
 */
class RequestBackgroundCheck {

    public function __construct()
    {

    }

    public function execute()
    {
        if($i->background_check == 0 && $_REQUEST['background_code'] == '1'){
            $backgroundCheck = true;
        }else{
            $backgroundCheck = false;
        }

        if($_REQUEST['background_code'] == '1'){
            $i->background_check = 1;
          }else if($_REQUEST['background_code'] == '0'){
            $i->background_check = 0;
        }


    }
}
