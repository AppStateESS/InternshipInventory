<?php
namespace Intern\Command;

use \Intern\AffiliationAgreementFactory;

class AffiliateListRest {

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

    // TODO: this should return the table of affiliation agreement objects
    public function get()
    {
        $db = \Database::newDB();
		$pdo = $db->getPDO();

		$sql = "SELECT intern_affiliation_agreement.name,
					   intern_affiliation_agreement.end_date,
                       intern_affiliation_agreement.id,
                       intern_affiliation_agreement.auto_renew
				FROM intern_affiliation_agreement
                ORDER BY intern_affiliation_agreement.end_date ASC";

		$sth = $pdo->prepare($sql);

		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

		return $result;
    }
}
