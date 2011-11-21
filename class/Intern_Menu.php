<?php
/**
 * handle the menu options based on who is logged in
 * @author Micah Carter <mcarter at tux dot appstate dot edu>
 **/

class Intern_Menu {

    function showMenu() {
        PHPWS_Core::initModClass('intern','UI/Intern_MenuUI.php');
        $disp = new Intern_MenuUI();
        return $disp->display();
    }
}
?>
