<?php
namespace App\Services;

use App\Core\Mail;
use App\Core\Token;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailO2
{
  //----------------------------------------------------------------------
  public function __construct(private MailerInterface $mailer) { }
  //----------------------------------------------------------------------
  public function sendRegisterConfirmation(String $to) : Token {
    // Get a token + selector object
    $tks =  new Token('/robot/registeruserconfirmed'); 
    $message = $this->buildRegistrationMessage($_ENV['MAIL_REGISTER_SUBJECT'], $tks);
    // ------------------------------------------------------------------------
    // Send email: here we use the symfony standard package
    // Remember a worker has to consume the emails otherwise they won't be sent
    // Can be launched with this command :  
    //                  php bin/console messenger:consume async -vv
    // or
    //                  php bin/console messenger:consume async
    // This should be automatically started in production with a cron job...
    // The consumer must be stopped properly with this command:
    //                  php bin/console messenger:stop
    // ------------------------------------------------------------------------
    $email = (new Email())
        ->from($_ENV["MAIL_FROM"])
        ->to($to)
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        ->replyTo($_ENV["MAIL_FROM"])
        //->priority(Email::PRIORITY_HIGH)
        ->subject($_ENV['MAIL_REGISTER_SUBJECT'])
        ->html($message);
    try {
        $this->mailer->send($email);
        return $tks;
    } catch (TransportExceptionInterface $e) {
        return null;
    }            
  }
  //----------------------------------------------------------------------
  private function buildRegistrationMessage(string $subject, Token $tks) 
  {
    $atlast = date('d-m-Y h:i',$tks->getExpires());
    date_default_timezone_set('Europe/Paris');
    $message = "<p>".$subject."</p>";
    $message .= "<p>Click on this link to confirm</p>";
    $message .= "<a href='".$tks->getUrl()."'>".$tks->getUrl()."</a>";
    $message .= '<p>Proceed before '.$atlast.'</p>';
    return $message;
  }  
  //----------------------------------------------------------------------
  public function sendPasswordReset(string $subject, $userpseudo) {

  }
}

?>