<?php

namespace App\Entity;

use App\Repository\OldMemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OldMemberRepository::class)]
class OldMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_accepted = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_leave = null;

    #[ORM\Column(length: 100)]
    private ?string $leave_reason = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $uuid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDateAccepted(): ?\DateTimeInterface
    {
        return $this->date_accepted;
    }

    public function setDateAccepted(?\DateTimeInterface $date_accepted): static
    {
        $this->date_accepted = $date_accepted;

        return $this;
    }

    public function getDateLeave(): ?\DateTimeInterface
    {
        return $this->date_leave;
    }

    public function setDateLeave(\DateTimeInterface $date_leave): static
    {
        $this->date_leave = $date_leave;

        return $this;
    }

    public function getLeaveReason(): ?string
    {
        return $this->leave_reason;
    }

    public function setLeaveReason(string $leave_reason): static
    {
        $this->leave_reason = $leave_reason;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
}
