<?php

/**
 * Sysinventory_Document_Manager
 *
 * A subclass is needed because we need to do a little
 * extra work when a file is submitted. Also, the ID
 * of the new file is necessary to insert a new line in sysinventory_document.
 *
 * @author Robert Bost <bostrt at tux dot appstate dot edu>
 */
PHPWS_Core::initModClass('filecabinet', 'Document_Manager.php');
class Sysinventory_Document_Manager extends FC_Document_Manager
{
    /**
     * @Override FC_Document_Manager::edit()
     *
     * This is a copy and paste of the overridden function 
     * except that the module for the form is set to sysinventory.
     * Also, check if the folder has been set. If not create
     * one for the user and load it.
     */
    public function edit()
    {
        if (empty($this->document)) {
            $this->loadDocument();
        }

        // If the folder ID is zero then it was not found
        // when Sysinventory_Folder::documentUpload() was called.
        // Create one and load it.
        if($this->folder->id == 0){
            PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Folder.php');
            PHPWS_Core::requireInc('filecabinet', 'defines.php');
            $folder = new Sysinventory_Folder();
            $folder->module_created = 'sysinventory';
            $folder->title = 'system documents';
            $folder->public_folder = FALSE;
            $folder->ftype = DOCUMENT_FOLDER;
            $folder->loadDirectory();
            $folder->save();
            $this->folder = $folder;
        }

        PHPWS_Core::initCoreClass('File.php');

        $form = new PHPWS_FORM;
        $form->addHidden('module',    'sysinventory');
        $form->addHidden('sysId',     $_REQUEST['sysId']);
        $form->addHidden('action',    'post_document_upload');
        $form->addHidden('ms',        $this->max_size);
        $form->addHidden('folder_id', $this->folder->id);

        $form->addFile('file_name');
        $form->setSize('file_name', 30);
        $form->setLabel('file_name', dgettext('filecabinet', 'Document location'));

        $form->addText('title', $this->document->title);
        $form->setSize('title', 40);
        $form->setLabel('title', dgettext('filecabinet', 'Title'));

        $form->addTextArea('description', $this->document->description);
        $form->setLabel('description', dgettext('filecabinet', 'Description'));

        if ($this->document->id) {
            $form->addTplTag('FORM_TITLE', dgettext('filecabinet', 'Update file'));
            $form->addHidden('document_id', $this->document->id);
            $form->addSubmit('submit', dgettext('filecabinet', 'Update'));
        } else {
            $form->addTplTag('FORM_TITLE', dgettext('filecabinet', 'Upload new file'));
            $form->addSubmit('submit', dgettext('filecabinet', 'Upload'));
        }

        $form->addButton('cancel', dgettext('filecabinet', 'Cancel'));
        $form->setExtra('cancel', 'onclick="window.close()"');

        $form->setExtra('submit', 'onclick="this.style.display=\'none\'"');

        if ($this->document->id && Current_User::allow('filecabinet', 'edit_folders', $this->folder->id, 'folder', true)) {
            Cabinet::moveToForm($form, $this->folder);
        }

        $template = $form->getTemplate();

        if ($this->document->id) {
            $template['CURRENT_DOCUMENT_LABEL'] = dgettext('filecabinet', 'Current document');
            $template['CURRENT_DOCUMENT_ICON']  = $this->document->getIconView();
            $template['CURRENT_DOCUMENT_FILE']  = $this->document->file_name;
        }
        $template['MAX_SIZE_LABEL'] = dgettext('filecabinet', 'Maximum file size');

        $sys_size = str_replace('M', '', ini_get('upload_max_filesize'));

        $sys_size = $sys_size * 1000000;

        if((int)$sys_size < (int)$this->max_size) {
            $template['MAX_SIZE'] = sprintf(dgettext('filecabinet', '%d bytes (system wide)'), $sys_size);
        } else {
            $template['MAX_SIZE'] = sprintf(dgettext('filecabinet', '%d bytes'), $this->max_size);
        }

        if ($this->document->_errors) {
            $template['ERROR'] = $this->document->printErrors();
        }

        Layout::add(PHPWS_Template::process($template, 'filecabinet', 'document_edit.tpl'));
    }

    /**
     * @Override FC_Document_Manager::postDocumentUpload().
     *
     * This is a copy and past of the overriden function except
     * that we now create a new Sysinventory_Document object
     * and save it to databse.
     */
    public function postDocumentUpload()
    {
        // importPost in File_Common
        $result = $this->document->importPost('file_name');

        if (PEAR::isError($result)) {
            PHPWS_Error::log($result);
            $vars['timeout'] = '3';
            $vars['refresh'] = 0;
            javascript('close_refresh', $vars);
            return dgettext('filecabinet', 'An error occurred when trying to save your document.');
        } elseif ($result) {
            $result = $this->document->save();

            if (PHPWS_Error::logIfError($result)) {
                $content = dgettext('filecabinet', '<p>Could not upload file to folder. Please check your directory permissions.</p>');
                $content .= sprintf('<a href="#" onclick="window.close(); return false">%s</a>', dgettext('filecabinet', 'Close this window'));
                Layout::nakedDisplay($content);
                exit();
            }

            PHPWS_Core::initModClass('filecabinet', 'File_Assoc.php');
            FC_File_Assoc::updateTag(FC_DOCUMENT, $this->document->id, $this->document->getTag());

            $this->document->moveToFolder();
            
            // If the document's id is set in the request
            // then we are updating a file. Not need to insert 
            // it into database.
            if(!isset($_REQUEST['document_id'])){
                // Save Sysinventory_Document in database.
                PHPWS_Core::initModClass('sysinventory', 'Sysinventory_Document.php');
                $doc = new Sysinventory_Document();
                $doc->system_id = $_REQUEST['sysId'];
                $doc->document_fc_id = $this->document->id;
                $result = $doc->save();
            }

            // Choose the proper notification text...
            if($_REQUEST['document_id'] && $result){
                NQ::simple('sysinventory', SYSI_SUCCESS, "File updated.");
            }else if($result){
                NQ::simple('sysinventory', SYSI_SUCCESS, "File uploaded.");
            }else if(PHPWS_Error::logIfError($result)){
                NQ::simple('sysinventory', SYSI_ERROR, $result->toString());
            }
            
            if (!isset($_POST['im'])) {
                javascript('close_refresh');
            } else {
                javascript('modules/filecabinet/refresh_manager', array('document_id'=>$this->document->id));
            }
            
        } else {
            return $this->edit();
        }
    }
}
?>
