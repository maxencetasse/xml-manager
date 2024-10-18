<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addDate = null;

    #[ORM\Column(length: 255)]
    private ?string $baseName = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn()]
    private ?User $user = null;

    /**
     * @var Collection<int, Node>
     */
    #[ORM\OneToMany(mappedBy: 'document', targetEntity: Node::class, orphanRemoval: true)]
    private Collection $nodes;

    public function __construct()
    {
        $this->nodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->addDate;
    }

    public function setAddDate(\DateTimeInterface $addDate): static
    {
        $this->addDate = $addDate;

        return $this;
    }

    public function getBaseName(): ?string
    {
        return $this->baseName;
    }

    public function setBaseName(string $baseName): static
    {
        $this->baseName = $baseName;

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
     * @return Collection<int, Node>
     */
    public function getNodes(): Collection
    {
        return $this->nodes;
    }

    public function addNode(Node $node): static
    {
        if (!$this->nodes->contains($node)) {
            $this->nodes->add($node);
            $node->setDocument($this);
        }

        return $this;
    }

    public function removeNode(Node $node): static
    {
        if ($this->nodes->removeElement($node)) {
            // set the owning side to null (unless already changed)
            if ($node->getDocument() === $this) {
                $node->setDocument(null);
            }
        }

        return $this;
    }
}
