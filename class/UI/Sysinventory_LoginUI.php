<?php
class Sysinventory_LoginUI {
    var $error = NULL;
    var $panel = NULL;

    function display() {
        
        $this->panel = $this->setupPanel();

        $tags = array();
        $tags['TITLE'] = _('System Inventory Login');
        $tags['TEXT'] = _('Please enter your username and password');

        $tags['USERNAME_LABEL'] = _('Username: ');
        $tags['PASSWORD_LABEL'] = _('Password: ');

        if (isset($this->error)) {
            $tags['ERROR_MESSAGE'] = $this->error;
        }

        $form = &new PHPWS_Form();
        $form->setAction('index.php?module=sysinventory&action=login');

        # If there was an error, try to fill in the username field
        if(isset($this->error) && isset($_REQUEST['username'])){
            $form->addText('username',$_REQUEST['username']);
        }else{
            $form->addText('username');
        }
        
        $form->addPassword('password');
        $form->addSubmit('submit_button','Login');

        $form->mergeTemplate($tags);
        $tags = $form->getTemplate();
        $content = PHPWS_Template::process($tags,'sysinventory','login.tpl');
        $this->panel->setContent($content);
        return $this->panel->display();
    }

    function setError($error_msg){
        $this->error = $error_msg;
    }

    function &setupPanel() {
        $panel = &new PHPWS_Panel('actions');
        $panel->disableSecure();
        $panel->setModule('sysinventory');
        $panel->setPanel('panel.tpl');
        return $panel;
    }

}
 
?>
