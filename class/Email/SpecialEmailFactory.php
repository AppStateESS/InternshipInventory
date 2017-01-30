<?php

namespace Intern\Email;
use Intern\Internship;
use Intern\Agency;
use DomainException;

/**
 * Allows for the instantiation and sending of specialized email types from
 * a single factory class. Simplifies the creation of specialized emails for
 * use in other classes.
 */
class SpecialEmailFactory {

  /**
   * Takes a specialized email type and its necessary parameters. Starts the
   * process of sending a specialized email; a call to this constructor
   * WILL send an email. No return or assignment is necessary due to the
   * structure of Email. See Email->sendSpecialMessage() for more documentation.
   *
   * @param String      $emailType       Type of specialized email to send.
   * @param Internship  $i
   * @param Agency      $a
   * @param String      $note
   * @param boolean     $backgroundCheck
   * @param boolean     $drugCheck
   */
  public function sendEmail($emailType, Internship $i,
  Agency $a = null, $note = null, $backgroundCheck = false,
  $drugCheck = false)
  {
    switch($emailType) {
      case "SendBackgroundCheckEmail":
        new SendBackgroundCheckEmail($i, $a, null, $backgroundCheck, $drugCheck);
        break;
      case "SendGradSchoolNotification":
        new SendGradSchoolNotification($i, $a);
        break;
      case "SendInternshipCancelNotice":
        new SendInternshipCancelNotice($i);
        break;
      case "SendIntlInternshipCreateNotice":
        new SendIntlInternshipCreateNotice($i);
        break;
      case "SendIntlInternshipCreateNoticeStudent":
        new SendIntlInternshipCreateNoticeStudent($i);
        break;
      case "SendOIEDCancellationEmail":
        new SendOIEDCancellationEmail($i, $a);
        break;
      case "SendOIEDCertifiedNoticeEmail":
        new SendOIEDCertifiedNoticeEmail($i, $a);
        break;
      case "SendOIEDReinstateEmail":
        new SendOIEDReinstateEmail($i, $a);
        break;
      case "SendRegistrarEmail":
        new SendRegistrarEmail($i, $a);
        break;
      case "SendRegistrationConfirmationEmail":
        new SendRegistrationConfirmationEmail($i, $a);
        break;
      case "SendRegistrationIssueEmail":
        new SendRegistrationIssueEmail($i, $a, $note);
        break;
      default:
        throw new DomainException("'" . $emailType . "' is not a valid Email type");
    }
  }
}
