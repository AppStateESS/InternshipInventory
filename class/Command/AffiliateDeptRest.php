<?php
namespace Intern\Command;

use \Intern\PdoFactory;
use \Intern\DepartmentFactory;

class AffiliateDeptRest {

    public function execute()
    {
        /* Check if user should have access to Affiliate Agreement page */
        if(!\Current_User::allow('intern', 'affiliation_agreement')){
            \NQ::simple('intern', \Intern\UI\NotifyUI::WARNING, 'You do not have permission to add Affiliation Agreements.');
            throw new \Intern\Exception\PermissionException('You do not have permission to add Affiliation Agreements.');
        }

        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $data = $this->get();
                echo (json_encode($data));
                exit;
            case 'POST':
                $this->post();
                exit;
            case 'DELETE' :
                $this->delete();
                exit;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }
    }

    public function get()
    {
        $affiliationId = $_REQUEST['affiliation_agreement_id'];

        if (is_null($affiliationId) || !isset($affiliationId)) {
            throw new \InvalidArgumentException('Missing Affiliation ID.');
        }

        $db = PdoFactory::getPdoInstance();

        $query = "SELECT intern_department.id, intern_department.name FROM intern_agreement_department JOIN intern_department ON intern_agreement_department.department_id = intern_department.id
        WHERE agreement_id = :id and hidden = 0
        ORDER BY intern_department.name ASC";

        $params= array('id' => $affiliationId);
        $stmt = $db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, '\Intern\DepartmentDB');
    }

    public function post()
    {
        $deptId = $_REQUEST['department'];
        $affiliationId = $_REQUEST['affiliation_agreement_id'];

        // Sanity checking
        if (is_null($deptId) || !isset($deptId)) {
            throw new \InvalidArgumentException('Missing Department Id.');
        }

        if (is_null($affiliationId) || !isset($affiliationId)) {
            throw new \InvalidArgumentException('Missing Affiliation ID.');
        }


        $db = PdoFactory::getPdoInstance();

        $query = "INSERT INTO intern_agreement_department
        (agreement_id, department_id)
        VALUES (:agreementId, :deptId)";

        $values = array('agreementId' => $affiliationId, 'deptId' => $deptId);

        $stmt = $db->prepare($query);
        $stmt->execute($values);
    }

    public function delete()
    {
        $deptId = $_REQUEST['department'];
        $affiliationId = $_REQUEST['affiliation_agreement_id'];

        // Sanity checking
        if (is_null($deptId) || !isset($deptId)) {
            throw new \InvalidArgumentException('Missing Department Name.');
        }

        if (is_null($affiliationId) || !isset($affiliationId)) {
            throw new \InvalidArgumentException('Missing Affiliation ID.');
        }

        $db = PdoFactory::getPdoInstance();

        $query = "DELETE FROM intern_agreement_department
        WHERE agreement_id = :agreementId AND department_id = :deptId";

        $values = array('agreementId' => $affiliationId, 'deptId' => $deptId);

        $stmt = $db->prepare($query);
        $stmt->execute($values);
    }

}
