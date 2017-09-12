<?php

namespace Intern\DataProvider\Term;

use Intern\TermInfo;

abstract class TermInfoProvider {

    public abstract function getTermInfo(string $termCode): TermInfo;
}
