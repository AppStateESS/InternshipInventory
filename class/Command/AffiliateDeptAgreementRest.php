<?php
namespace Intern\Command;

use \Intern\PdoFactory;

class AffiliateDeptAgreementRest {

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
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                exit;
        }

    }

    public function get()
    {
        $dept = $_GET['dept'];

        if (is_null($dept) || !isset($dept)) {
            throw new \InvalidArgumentException('Missing Department ID.');
        }

        $db = \Database::newDB();
        $pdo = $db->getPDO();

		$sql = "SELECT intern_affiliation_agreement.name, intern_affiliation_agreement.end_date, intern_affiliation_agreement.id
                FROM intern_affiliation_agreement JOIN intern_agreement_department on intern_affiliation_agreement.id = intern_agreement_department.agreement_id
                JOIN intern_department on intern_department.id = intern_agreement_department.department_id
                WHERE intern_agreement_department.department_id = :dept
                ORDER BY intern_affiliation_agreement.end_date DESC";

        $params = array('dept' => $dept);
        $sth = $pdo->prepare($sql);
        $sth->execute($params);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
}
