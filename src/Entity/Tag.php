<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Quack>
     */
    #[ORM\ManyToMany(targetEntity: Quack::class, mappedBy: 'Tags')]
    private Collection $quacks;

    public function __construct()
    {
        $this->quacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Quack>
     */
    public function getQuacks(): Collection
    {
        return $this->quacks;
    }

    public function addQuack(Quack $quack): static
    {
        if (!$this->quacks->contains($quack)) {
            $this->quacks->add($quack);
            $quack->addTag($this);
        }

        return $this;
    }

    public function removeQuack(Quack $quack): static
    {
        if ($this->quacks->removeElement($quack)) {
            $quack->removeTag($this);
        }

        return $this;
    }
}
