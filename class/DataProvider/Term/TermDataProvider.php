<?php

namespace Intern\DataProvider\Term;

use Intern\TermInfo;

abstract class TermDataProvider {

    public abstract function getTermInfo(string $termCode): TermInfo;
}
