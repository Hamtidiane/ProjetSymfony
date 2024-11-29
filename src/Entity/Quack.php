<?php

namespace App\Entity;

use App\Repository\QuackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuackRepository::class)]
class Quack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'quacks')]
    private Collection $Tags;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
		
		public function __construct(){
			$this->created_at = new \DateTimeImmutable();
            $this->Tags = new ArrayCollection();
		}

        /**
         * @return Collection<int, Tag>
         */
        public function getTags(): Collection
        {
            return $this->Tags;
        }

        public function addTag(Tag $tag): static
        {
            if (!$this->Tags->contains($tag)) {
                $this->Tags->add($tag);
            }

            return $this;
        }

        public function removeTag(Tag $tag): static
        {
            $this->Tags->removeElement($tag);

            return $this;
        }
}
