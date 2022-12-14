<?php
namespace App\Core;

use App\Core\Token;

/**
 * Gère l'envoi, la création du token
 * Contruit le message envoyé
 * Stocke dans la bdd la mailrequest
 */
abstract class Mail 
{
  protected $to;
  protected $from = 'noreply@alaskastory.fr';
  protected $reply = 'noreply@alaskastory.fr';

  /**
   * constructeur de la classe Mail
   *
   * @param string $to destinataire
   * @param [type] $theclass classe de l'appelant 
   */
  public function __construct(string $to, $theclass)
  {
    $this->to = $to;
  }
  
  /**
   * Envoie le mail à l'utilisateur qui cherche à s'enregistrer
   *
   * @param string $subject
   * @param [type] $userpseudo
   */
  abstract protected function sendRegisterConfirmation(string $subject, $userpseudo);
  
  //----------------------------------------------------------------------
  public function createToken($url) 
  {
    return new Token($url);
  }
  
  /**
   * Fabrique le mail
   *
   * @param string $subject
   * @param TokenSelector $tks
   */
  public function buildMessage(string $subject, Token $tks) 
  {
    date_default_timezone_set('Europe/Paris');
    $message = "<p>We received a register request</p>";
    $message = "<p>".$subject."</p>";
    $message .= "<p>Click on this link to confirm</p>";
    $atlast = date('d-m-Y h:i',$tks->getExpires());
    $message .= '<p>Proceed before '.$atlast.'</p>';
    $message .= "<a href='".$tks->getUrl()."'>".$tks->getUrl()."</a>";
    return $message;
  }
  
  /**
   * Ajoute dans la base de l'envoi du mail à l'utilisateur
   *
   * @param [type] $userpseudo
   * @param [type] $tks
   */
  public function storeMailRequest($userpseudo, $tks, $actionType) {
    // // Insert a reset record used to process the user's answer when clicking oin the mail
    // $resetdb = new ResetDB();
    // $resetdb->request($actionType, $userpseudo, 
    //         $tks->getSelector(), 
    //         $tks->getToken(), 
    //         $tks->getExpires());
  }
}

?>