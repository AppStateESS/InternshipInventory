<?php

interface DbStorable {

    public static function getTableName();
    public function extractVars();
}
