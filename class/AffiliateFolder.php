<?php

namespace Intern;

\PHPWS_Core::initModClass('filecabinet', 'Folder.php');

/**
 * AffiliateFolder
 *
 *
 * @author Chris Detsch
 */
class AffiliateFolder extends \Folder
{
    /**
     * Similar to Folder::uploadLink except this one takes a internship id as parameter
     * and links to intern module instead of filecabinet.
     */
    public function documentUpload($affiliationId)
    {
        $link_var = array();
        $link_var['folder_id'] = $this->id;
        $link_var['action'] = 'uploadAffiliationAgreemenet';
        $link_var['affiliate'] = $affiliationId;

        $link = new \PHPWS_Link(null, 'intern', $link_var, true);
        $link->convertAmp(false);
        $link->setSalted();

        $vars = array();
        $vars['address'] = $link->getAddress();

        $label = dgettext('filecabinet', 'Add document');

        \javascript('open_window');
        return '<button type="button" class="btn btn-default btn-md" onClick="javascript:open_window(\'' . $link->getAddress() . '\', 600, 600, \'default970975506\', 1); return false;"><i class="fa fa-upload"></i> ' . $label . '</button>';
    }
}
