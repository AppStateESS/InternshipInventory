<?php

  /**
   * SearchUI
   *
   * Search/Sort by student names and banners, department name, 
   * grad/undergrad, and term.
   * 
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */

PHPWS_Core::initModClass('intern', 'UI/UI.php');
class SearchUI implements UI
{
    public static function display()
    {
        PHPWS_Core::initModClass('intern', 'Term.php');
        PHPWS_Core::initModClass('intern', 'Department.php');

        // Set up search fields
        $searchForm = new PHPWS_Form();
        $terms = Term::getTermsAssoc();
        $searchForm->addMultiple('term_select', $terms);
        $searchForm->setLabel('term_select', 'Term');

        // Deity can search for any department. Other users are restricted.
        if(Current_User::isDeity()){
            $depts = Department::getDepartmentsAssoc();
        }else{
            $depts = Department::getDepartmentsAssocForUsername(Current_User::getUsername());
        }

        $searchForm->addMultiple('dept', $depts);
        $searchForm->setLabel('dept', 'Department');

        $searchForm->addText('name');
        $searchForm->setLabel('name', "Name or Banner ID");
        $searchForm->setAction('index.php?module=intern&action=results');
        $searchForm->addSubmit('submit', 'Search');

        // Javascript...
        javascript('/jquery/');
        javascript('/modules/intern/resetSearch');

        return PHPWS_Template::process($searchForm->getTemplate(), 'intern', 'search.tpl');
    }

}

?>