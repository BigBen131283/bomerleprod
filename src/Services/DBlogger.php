<?php

namespace App\Services;

use DateTime;
use DateTimeZone;
use App\Entity\Dblog;
use Doctrine\Persistence\ManagerRegistry;

class DBlogger {

  // -------------------------------------------------------------------------
  public function __construct(private ManagerRegistry $mgr) { }
  // -------------------------------------------------------------------------
  private function log(string $message, int $severity = Dblog::DEBUG, string $action = 'GENERIC',  string $module = 'GENERIC') {

    // $dblogrepo = $this->mgr->getRepository(Dblog::class);
    $dblogrepo = $this->mgr->getManager();
    $dblog = new Dblog();
    $dblog->setMessage($message);
    $dblog->setSeverity($severity);
    $dblog->setModule($module);
    $dblog->setAction($action);

    $eventtime = new DateTime('now', new \DateTimeZone('Europe/Paris'));
    $utctime = new DateTime('now', new \DateTimeZone('UTC'));

    $dblog->setLogtime($eventtime);
    $dblog->setUtctime($utctime);
    $dblogrepo->persist($dblog);
    $dblogrepo->flush();
  }
  // -------------------------------------------------------------------------
  public function debug(string $message, string $action = 'GENERIC',  string $module = 'GENERIC') {
    $this->log($message, Dblog::DEBUG, $action,  $module);
  }
  // -------------------------------------------------------------------------
  public function info(string $message, string $action = 'GENERIC',  string $module = 'GENERIC') {
    $this->log($message, Dblog::INFORMATIONAL, $action,  $module);
  }
  // -------------------------------------------------------------------------
  public function warning(string $message, string $action = 'GENERIC',  string $module = 'GENERIC') {
    $this->log($message, Dblog::WARNING, $action,  $module);
  }
  // -------------------------------------------------------------------------
  public function error(string $message, string $action = 'GENERIC',  string $module = 'GENERIC') {
    $this->log($message, Dblog::ERROR, $action,  $module);
  }
  // -------------------------------------------------------------------------
  public function fatal(string $message, string $action = 'GENERIC',  string $module = 'GENERIC') {
    $this->log($message, Dblog::FATAL, $action,   $module);
  }
}