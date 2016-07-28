<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;

class BackgroundCheck extends Email{

  /**
   * Sends the Background or Drug check notification email.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public function __construct(Internship $i, Agency $a, $backgroundCheck, $drugCheck) {
    echo("CLASS: BackgroundCheck");
    self::sendSpecialMessage($i, $a, null, $backgroundCheck, $drugCheck);
  }

  /**
   * Adds information to email regarding background and/or drug checking.
   */
  protected function setUpSpecial() {
    $background = '';
    $drugTest = '';

    $this->tpl['AGENCY'] = $this->agency->getName();

    if ($this->backgroundCheck)
        $background = 'Background';

    if ($this->drugCheck)
        $drugTest = 'Drug';

    if ($this->backgroundCheck && $this->drugCheck)
    {
        $this->subject = 'Internship Background/Drug Check Needed ' . $this->internship->getFullName();
        $this->tpl['CHECK'] = $background . '/' . $drugTest;
    }else{
        $this->subject = 'Internship ' . $background . $drugTest . ' Check Needed ' . $this->internship->getFullName();
        $this->tpl['CHECK'] = $background . $drugTest;
    }

    $this->to = $this->settings->getBackgroundCheckEmail();
  }
}
