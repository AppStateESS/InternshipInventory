<?php

namespace Intern\Command;

use \Intern\InternshipFactory;
use \Intern\DatabaseStorage;

class DocumentRest {

	public function execute()
	{
		$id = $_REQUEST['internship_id'];
		$db = InternDocument::getDB();
        $db->addWhere('internship_id', $id);
        $docs = $db->getObjects('\Intern\InternDocument');
		// Document list
		if (!is_null($docs)) {
			foreach ($docs as $doc) {
				$tpl['docs'][] = array('DOWNLOAD' => $doc->getDownloadLink('blah'),
				'DELETE' => $doc->getDeleteLink());
			}
		}

		// Document upload button
		$folder = new InternFolder(InternDocument::getFolderId());
		$tpl['UPLOAD_DOC'] = $folder->documentUpload($this->intern->id);
	}

}
