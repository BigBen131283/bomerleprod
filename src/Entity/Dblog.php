<?php

namespace App\Entity;

use App\Repository\DblogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DblogRepository::class)]
class Dblog
{
    const DEBUG = 0;
    const INFORMATIONAL = 1;
    const WARNING = 2;
    const ERROR = 3;
    const FATAL = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?int $severity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $logtime = null;

    #[ORM\Column(length: 255)]
    private ?string $module = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $utctime = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $action = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSeverity(): ?int
    {
        return $this->severity;
    }

    public function setSeverity(int $severity): self
    {
        $this->severity = $severity;

        return $this;
    }

    public function getLogtime(): ?\DateTimeInterface
    {
        return $this->logtime;
    }

    public function setLogtime(\DateTimeInterface $logtime): self
    {
        $this->logtime = $logtime;

        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(string $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getUtctime(): ?\DateTimeInterface
    {
        return $this->utctime;
    }

    public function setUtctime(\DateTimeInterface $utctime): self
    {
        $this->utctime = $utctime;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }
}
