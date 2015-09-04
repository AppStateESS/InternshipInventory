<?php

namespace Intern;

class AcademicMajorList {

    private $undergradMajors;
    private $graduateMajors;

    /**
     * Creates an AcademicMajorList object given an array of stdClass objects containing
     * undergraduate and graduate majors, and potentially including duplicates (from BannerMajorsProvider).
     *
     * @see BannerMajorsProvider
     * @param Array<stdClass> $majorsArray Array of majors, one major per object
     */
    public function __construct(Array $majorsArray)
    {
        $undergradMajors = array();
        $graduateMajors = array();

        // Check for duplicate majors while sorting the majors into undergrad vs grad
        foreach($majorsArray as $major)
        {
            if($major->levl == MajorsProvider::LEVEL_UNDERGRAD) {
                $this->addIfNotDuplicate($major, $undergradMajors);
            } else if($major->levl == MajorsProvider::LEVEL_GRADUATE) {
                $this->addIfNotDuplicate($major, $graduateMajors);
            }
        }

        // Translate each stdClass object into an AcademicMajor object and store it in the member variables
        $this->undergradMajors = array();
        $this->graduateMajors = array();

        foreach($undergradMajors as $major){
            $this->undergradMajors[] = new AcademicMajor($major->major_code, $major->major_desc, $major->levl);
        }

        foreach($graduateMajors as $major){
            $this->graduateMajors[] = new AcademicMajor($major->major_code, $major->major_desc, $major->levl);
        }

        $this->sortList($this->undergradMajors);
        $this->sortList($this->graduateMajors);
    }

    public function getUndergradMajorsAssoc()
    {
        return $this->toAssocList($this->undergradMajors);
    }

    public function getGraduateMajorsAssoc()
    {
        return $this->toAssocList($this->graduateMajors);
    }

    private function toAssocList($majorList){
        $list = array();

        foreach($majorList as $major){
            $list[$major->getCode()] = $major->getDescription();
        }

        return $list;
    }

    /**
     * Adds the array represnting a major to the set of majors if it is not already in the list.
     * Prevents duplciate major arrays from being added to the list.
     *
     * @param Array $major The array holding a single major
     * @param Array $destArray A reference to an array of Major arrays, which we'll conditionally append $major to
     */
    private function addIfNotDuplicate(\stdClass $major, Array &$destArray)
    {
        // Look through each sub-array in the set of majors
        foreach($destArray as $m){
            // If the sub-array we're looking at matches the single major we were given, then we've
            // found a duplicate and we can stop looking any further
            if($m->major_code == $major->major_code){
                return;
            }
        }

        // If we didn't find any duplicates (i.e. $major did not exist in $destArray), then add it
        $destArray[] = $major;
    }

    private function sortList(&$list)
    {
        usort($list, array('self', 'compareFunc'));
    }

    public static function compareFunc($a, $b)
    {
        return strcasecmp($a->getDescription(), $b->getDescription());
    }
}
