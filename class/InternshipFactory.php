<?php

namespace Intern;

class InternshipFactory {

    /**
     * Generates an Internship object by attempting to load the internship from the database with the given id.
     *
     * @param int $id
     * @returns Internship
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws InternshipNotFoundException
     */
    public static function getInternshipById($id)
    {
        if(is_null($id) || !isset($id)){
            throw new \InvalidArgumentException('Internship ID is required.');
        }

        if($id <= 0){
            throw new \InvalidArgumentException('Internship ID must be greater than zero.');
        }

        $internship = new Internship;

        $db = new \PHPWS_DB('intern_internship');
        $db->addWhere('id', $id);
        $result = $db->loadObject($internship);

        if (\PHPWS_Error::logIfError($result)) {
            throw new \Exception($result->toString());
        }

        if($internship->getId() == 0){
            \PHPWS_Core::initModClass('intern', 'exception/InternshipNotFoundException.php');
            throw new InternshipNotFoundException('Could not locate the requested internship.');
        }

        return $internship;
    }
}

?>
