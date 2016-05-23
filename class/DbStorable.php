<?php

namespace Intern;

interface DbStorable {

    public static function getTableName();
    public function extractVars();
    public function setId($id);
}
