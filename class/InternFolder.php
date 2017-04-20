<?php

namespace Intern;

use PHPWS_Link;
use \PHPWS_Core;

  /**
   * Intern_Folder
   *
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
PHPWS_Core::initModClass('filecabinet', 'Folder.php');
class InternFolder extends \Folder
{
    /**
     * Similar to Folder::uploadLink except this one takes a internship id as parameter
     * and links to intern module instead of filecabinet.
     */
    public function documentUpload($internshipId)
    {
        $link_var = array();
        $link_var['folder_id'] = $this->id;
        $link_var['action'] = 'upload_document_form';
        $link_var['internship'] = $internshipId;

        $link = new PHPWS_Link(null, 'intern', $link_var, true);
        $link->convertAmp(false);
        $link->setSalted();
        
        $vars = array();
        $vars['address'] = $link->getAddress();

        $label = dgettext('filecabinet', 'Add document');

        
        return array('label'=> $label, 'address'=> $link->getAddress());

        // javascript('open_window');
        // return '<button type="button" class="btn btn-default btn-sm" onClick="javascript:open_window(\'' . $link->getAddress() . '\', 600, 600, \'default970975506\', 1); return false;"><i class="fa fa-upload"></i> ' . $label . '</button>';
    }
}
