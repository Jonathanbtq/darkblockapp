<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column]
    private ?int $nb_vote = null;

    #[ORM\Column(length: 255)]
    private ?string $question_text = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'vote', targetEntity: VoteCount::class, orphanRemoval: true)]
    private Collection $voteCounts;

    public function __construct()
    {
        $this->voteCounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getNbVote(): ?int
    {
        return $this->nb_vote;
    }

    public function setNbVote(int $nb_vote): static
    {
        $this->nb_vote = $nb_vote;

        return $this;
    }

    public function getQuestionText(): ?string
    {
        return $this->question_text;
    }

    public function setQuestionText(string $question_text): static
    {
        $this->question_text = $question_text;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, VoteCount>
     */
    public function getVoteCounts(): Collection
    {
        return $this->voteCounts;
    }

    public function addVoteCount(VoteCount $voteCount): static
    {
        if (!$this->voteCounts->contains($voteCount)) {
            $this->voteCounts->add($voteCount);
            $voteCount->setVote($this);
        }

        return $this;
    }

    public function removeVoteCount(VoteCount $voteCount): static
    {
        if ($this->voteCounts->removeElement($voteCount)) {
            // set the owning side to null (unless already changed)
            if ($voteCount->getVote() === $this) {
                $voteCount->setVote(null);
            }
        }

        return $this;
    }
}
