<?php

namespace Intern\Command;

class SettingsRest {

    public function execute(){

        if(!\Current_User::allow('intern', 'affiliation_agreement')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to edit admin settings.');
            throw new \Intern\Exception\PermissionException('You do not have permission to edit admin settings.');
        }

        switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->post();
                echo (json_encode("Done"));
                exit;
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    public function post(){
        \PHPWS_Settings::set('intern','systemName',$_REQUEST['systemName']);
        \PHPWS_Settings::set('intern','registrarEmail',$_REQUEST['registrarEmail']);
        \PHPWS_Settings::set('intern','distanceEdEmail',$_REQUEST['distanceEdEmail']);
        \PHPWS_Settings::set('intern','internationalRegEmail',$_REQUEST['internationalRegEmail']);
        \PHPWS_Settings::set('intern','graduateRegEmail',$_REQUEST['graduateRegEmail']);
        \PHPWS_Settings::set('intern','gradSchoolEmail',$_REQUEST['gradSchoolEmail']);
        \PHPWS_Settings::set('intern','internationalOfficeEmail',$_REQUEST['internationalOfficeEmail']);
        \PHPWS_Settings::set('intern','fromEmail',$_REQUEST['fromEmail']);
        \PHPWS_Settings::set('intern','emailDomain',$_REQUEST['emailDomain']);
        \PHPWS_Settings::set('intern','backgroundCheckEmail',$_REQUEST['backgroundCheckEmail']);
        \PHPWS_Settings::set('intern','wsdlUri',$_REQUEST['wsdlUri']);
        \PHPWS_Settings::set('intern','unusualCourseEmail',$_REQUEST['unusualCourseEmail']);
        \PHPWS_Settings::set('intern','uncaughtExceptionEmail',$_REQUEST['uncaughtExceptionEmail']);

        \PHPWS_Settings::save('intern');
    }

    public function get(){
        return \PHPWS_Settings::get('intern');

    }
}
