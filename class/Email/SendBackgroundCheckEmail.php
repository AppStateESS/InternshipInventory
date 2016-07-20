<?php

namespace Intern\Email;

class BackgroundCheck extends Email{

  /**
   *  Sends the Background or Drug check notification email.
   *
   * @param Internship $i
   * @param Agency $agency
   */
  public function __construct(Internship $i, Agency $a, $backgroundCheck, $drugCheck)
  {
    self::sendSpecialMessage($i, $a, null, $backgroundCheck, $drugCheck);
  }

  public function setUpSpecial() {
    $background = '';
    $drugTest = '';

    $tpl['AGENCY'] = $agency->getName();

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
