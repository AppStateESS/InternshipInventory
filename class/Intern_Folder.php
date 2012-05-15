<?php
  /**
   * Intern_Folder
   *
   *
   * @author Robert Bost <bostrt at tux dot appstate dot edu>
   */
PHPWS_Core::initModClass('filecabinet', 'Folder.php');
class Intern_Folder extends Folder 
{
    /**
     * Similar to Folder::uploadLink except this one takes a internship id as parameter
     * and links to intern module instead of filecabinet.
     */
    public function documentUpload($internshipId)
    {
        $vars['width']   = 600;
        $vars['height']  = 600;

        $link_var['folder_id'] = $this->id;
        $link_var['action'] = 'upload_document_form';
        $link_var['internship'] = $internshipId;
        $label = dgettext('filecabinet', 'Add document(s)');

        $link = new PHPWS_Link(null, 'intern', $link_var, true);
        $link->convertAmp(false);
        $link->setSalted();
        $vars['address'] = $link->getAddress();
        $vars['title'] = & $label;
        
        $vars['label']   = $label;
        $vars['type']    = 'button';

        return javascript('open_window', $vars);
    }
}

?>