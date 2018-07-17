<?php

namespace Intern\Command;

use \phpws2\Database;
use \Intern\AffiliationAgreementFactory;

class DocumentRest
{

    public function execute()
    {
        /* Check if user should have access to folders */
        if (!\Current_User::isLogged()) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING,
                    'You do not have permission to files.');
            throw new \Intern\Exception\PermissionException('You do not have permission to files.');
        }

        $otherDir = \PHPWS_Settings::get('filecabinet', 'base_doc_directory') . "otherDocuments/";
        $contractDir = \PHPWS_Settings::get('filecabinet', 'base_doc_directory') . "contract/";
        $affiliationDir = \PHPWS_Settings::get('filecabinet',
                        'base_doc_directory') . "affiliation/";

        // Makes sure the folder of affiliation, contract, and otherDocuments are made
        if (!file_exists($otherDir)) {
            mkdir($otherDir);
        }
        if (!file_exists($contractDir)) {
            mkdir($contractDir);
        }
        if (!file_exists($affiliationDir)) {
            mkdir($affiliationDir);
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $data = $this->post($contractDir, $otherDir, $affiliationDir);
                echo (json_encode($data));
                exit;
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            case 'DELETE':
                $this->deleteFile();
                exit();
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    public function post($contractDir, $otherDir, $affiliationDir)
    {
        // List of known types taken from fileca$target_dir = $otherDir;binet. Only these accepted, update list of accept more.
        $known_documents = array('csv', 'doc', 'docx', 'odt', 'pdf', 'ppt', 'pptx', 'rtf',
            'tar', 'tgz', 'txt', 'xls', 'xlsx', 'xml', 'zip', 'gz', 'rar', 'ods', 'odp');
        $id = $_REQUEST['id'];

        // Name based on internship_id + users file name, ex. 71Document.txt for intern contracts and otherDocuments
        //Name based on affiliation_id + users file name for affiliation agreements uploaded
        $key = $_REQUEST['key'];
        $fileLongType = $_FILES[$key]['type'];
        $name = $_FILES[$key]['name'];
        $fileName = $id . $name;
        $type = $_REQUEST['type'];
        // See where the file is to be saved
        if ($type == 'other') {
            $target_dir = $otherDir;
        } else if ($type == 'affiliation') {
            $target_dir = $affiliationDir;
        } else {
            $target_dir = $contractDir;
        }
        $target_file = $target_dir . basename($fileName);

        // Check is the file type is one of the accepted ones
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
        for ($i = 0; $i < sizeof($known_documents); $i++) {
            if ($fileType == $known_documents[$i]) {
                $data['message'] = '';
                break;
            }
            $data['message'] = 'Did not save. File not a known type.';
        }

        if (file_exists($target_file)) {
            $data['message'] = 'Did not save. That file name already exist. Please rename the file.';
        }

        if (!is_writable($target_dir)) {
            $data['message'] = 'Did not save. File is not writable.';
        }

        $size = $_FILES[$key]['size'];
        // This is how filecabinet was getting the max file size, assuming it will always be in the format #M
        $maxSize = ini_get('upload_max_filesize');
        $sysSize = str_replace('M', '', $maxSize) * 1000000;
        if ($size > $sysSize) {
            $data['message'] = "Did not save. The file size was greater than " . $maxSize . ".";
        }

        if ($data['message'] == null) {
            if (move_uploaded_file($_FILES[$key]['tmp_name'], $target_file)) {
                $this->saveFile($id, $name, $fileName, $target_file, $type,
                        $fileLongType);
                $data['id'] = $this->getIdByName($fileName, $type);
                $data['name'] = $name;
            } else {
                $data['message'] = 'Sorry, there was an error uploading your file.';
            }
        }
        return $data;
    }

    // For database save
    public function saveFile($id, $name, $fileName, $target_file, $type,
            $fileLongType)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();

        if ($type != 'affiliation') {
            $sql = "INSERT INTO intern_contract_documents (id, internship_id, name, store_name, path_name, type, file_type)
					VALUES (nextval('intern_contract_documents_seq'), :internId, :name, :store, :pathN, :type, :fileT)";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('internId' => $id, 'name' => $name, 'store' => $fileName, 'pathN' => $target_file, 'type' => $type, 'fileT' => $fileLongType));
        } else {
            $sql = "INSERT INTO intern_affiliation_documents (id, affiliation_id, name, store_name, path_name, file_type)
					VALUES (nextval('intern_affiliation_documents_seq'), :affilId, :name, :store, :pathN, :fileT)";

            $sth = $pdo->prepare($sql);
            $sth->execute(array('affilId' => $id, 'name' => $name, 'store' => $fileName, 'pathN' => $target_file, 'fileT' => $fileLongType));
        }
    }

    public function deleteFile()
    {
        $id = $_REQUEST['id'];
        $docId = $_REQUEST['docId'];
        $type = $_REQUEST['type'];

        // From folder
        $target_file = $this->getPath($docId, $type);
        unlink($target_file);

        // From database
        $db = Database::newDB();
        $pdo = $db->getPDO();
        if ($type == 'affiliation') {
            $sql = "DELETE FROM intern_affiliation_documents
					WHERE id=:id";
        } else {
            $sql = "DELETE FROM intern_contract_documents
					WHERE id=:id";
        }

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id' => $docId));
    }

    public function get()
    {
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        // If the document's id is set then we're downloading the file
        if (isset($_REQUEST['docId'])) {
            $docId = $_REQUEST['docId'];
            $this->getDownLoad($docId, $type);
            return;
        }
        // If type is contract get the contract or it get all the otherDocuments or affiliations
        $db = Database::newDB();
        $pdo = $db->getPDO();

        if ($type != 'affiliation') {
            $sql = "SELECT id, name
					FROM intern_contract_documents
					WHERE internship_id=:id AND type=:type
					ORDER BY name ASC";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('id' => $id, 'type' => $type));
        } else {
            $sql = "SELECT id, name
					FROM intern_affiliation_documents
					WHERE affiliation_id=:id
					ORDER BY name ASC";
            $sth = $pdo->prepare($sql);
            $sth->execute(array('id' => $id));
        }

        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    // Makes the call to download the selected document
    public function getDownLoad($docId, $type)
    {
        $target_file = $this->getPath($docId, $type);
        if (!file_exists($target_file)) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING,
                    'The file could not be found to download.');
            throw new \Intern\Exception\WebServiceException('The file could not be found to download.');
        }
        $db = Database::newDB();
        $pdo = $db->getPDO();

        if ($type != 'affiliation') {
            $sql = "SELECT file_type
					FROM intern_contract_documents
					WHERE id=:id";
        } else {
            $sql = "SELECT file_type
					FROM intern_affiliation_documents
					WHERE id=:id";
        }

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id' => $docId));
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $file = file_get_contents($target_file);
        header('Content-type: ' . $result[0]['file_type']);
        header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Length: ' . strlen($file));
        header('Content-Disposition: inline; filename="' . basename($target_file) . '";');

        echo $file;

        exit;
    }

    // Gets the location of the file in the folders from the database
    public function getPath($docId, $type)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();
        if ($type != 'affiliation') {
            $sql = "SELECT path_name
					FROM intern_contract_documents
					WHERE id=:id";
        } else {
            $sql = "SELECT path_name
					FROM intern_affiliation_documents
					WHERE id=:id";
        }
        $sth = $pdo->prepare($sql);
        $sth->execute(array('id' => $docId));
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0]['path_name'];
    }

    // Gets the id for the document uploaded
    public function getIdByName($name, $type)
    {
        $db = Database::newDB();
        $pdo = $db->getPDO();
        if ($type != 'affiliation') {
            $sql = "SELECT id
					FROM intern_contract_documents
					WHERE store_name=:name AND type=:type";

            $sth = $pdo->prepare($sql);
            $sth->execute(array('name' => $name, 'type' => $type));
        } else {
            $sql = "SELECT id
					FROM intern_affiliation_documents
					WHERE store_name=:name";

            $sth = $pdo->prepare($sql);
            $sth->execute(array('name' => $name));
        }
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0]['id'];
    }

    // Used to see if there are any contracts uploaded or affiliation selected for notifications and export spreadsheet
    public static function contractAffilationSelected($id)
    {
        /* Check if user should have access to folders since this method does not go through execute */
        if (!\Current_User::isLogged()) {
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING,
                    'You do not have permission to files.');
            throw new \Intern\Exception\PermissionException('You do not have permission to files.');
        }

        // Gets what type of contract and if affiliation id is set
        $db = Database::newDB();
        $pdo = $db->getPDO();
        $sql = "SELECT contract_type, affiliation_agreement_id
				FROM intern_internship
				WHERE id=:id";

        $sth = $pdo->prepare($sql);
        $sth->execute(array('id' => $id));
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $info['type'] = $result[0]['contract_type'];
        $affilNum = $result[0]['affiliation_agreement_id'];

        // If contract then check if there is one uploaded, else see if the affiliation id is set and get its name
        if ($info['type'] == 'contract') {
            $dbC = Database::newDB();
            $pdoC = $dbC->getPDO();

            $sqlC = "SELECT id
					 FROM intern_contract_documents
					 WHERE internship_id=:id AND type=:type";

            $sthC = $pdoC->prepare($sqlC);
            $sthC->execute(array('id' => $id, 'type' => 'contract'));
            $resultCon = $sthC->fetchAll(\PDO::FETCH_ASSOC);
            if (sizeof($resultCon) > 0) {
                $info['value'] = 'Yes';
            } else {
                $info['value'] = 'No';
            }
        } else {
            if ($affilNum != null) {
                $affil = AffiliationAgreementFactory::getAffiliationById($affilNum);
                $info['value'] = $affil->getName();
            } else {
                $info['value'] = 'No';
            }
        }
        return $info;
    }

}
