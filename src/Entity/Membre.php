<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_accepted = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: ImageMembre::class)]
    private Collection $imageMembres;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $uuid = null;

    public function __construct()
    {
        $this->imageMembres = new ArrayCollection();
    }

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

    public function setDateAccepted(\DateTimeInterface $date_accepted): static
    {
        $this->date_accepted = $date_accepted;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, ImageMembre>
     */
    public function getImageMembres(): Collection
    {
        return $this->imageMembres;
    }

    public function addImageMembre(ImageMembre $imageMembre): static
    {
        if (!$this->imageMembres->contains($imageMembre)) {
            $this->imageMembres->add($imageMembre);
            $imageMembre->setMembre($this);
        }

        return $this;
    }

    public function removeImageMembre(ImageMembre $imageMembre): static
    {
        if ($this->imageMembres->removeElement($imageMembre)) {
            // set the owning side to null (unless already changed)
            if ($imageMembre->getMembre() === $this) {
                $imageMembre->setMembre(null);
            }
        }

        return $this;
    }
}
