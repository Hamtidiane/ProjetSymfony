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
		
		#[ORM\Column(nullable: true)]
		private ?string $picture = null;
		
		#[ORM\ManyToOne(inversedBy: 'quacks')]
		private ?Ducks $author = null;
		
		#[ORM\ManyToOne(inversedBy: 'children')]
		#[ORM\JoinColumn(nullable: true)] // Le parent est optionnel
		private ?Quack $parent = null;
		
		#[ORM\OneToMany(mappedBy: 'quack', targetEntity: Reply::class)]
		private Collection $replies;
		
		#[ORM\Column]
		private ?\DateTimeImmutable $created_at = null;
		
		/**
		 * @var Collection<int, Tag>
		 */
		#[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'quacks')]
		private Collection $Tags;
		
		public function __construct()
		{
			$this->created_at = new \DateTimeImmutable();
			$this->Tags = new ArrayCollection();
			$this->replies = new ArrayCollection(); // Initialisation de la collection replies
		}
		
		// Les autres méthodes (getters, setters, etc.) restent inchangées
		
		public function getReplies(): Collection
		{
			return $this->replies; // Retourne les réponses associées au Quack
		}
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
		
		public function getAuthor(): ?Ducks
		{
			return $this->author;
		}
		
		public function setAuthor(?Ducks $author): static
		{
			$this->author = $author;
			return $this;
		}
		
		public function getPicture(): ?string
		{
			return $this->picture;
		}
		
		public function setPicture(?string $picture): static
		{
			$this->picture = $picture;
			return $this;
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
		
		public function getParent(): ?Quack
		{
			return $this->parent;
		}
		
		public function setParent(?Quack $parent): static
		{
			$this->parent = $parent;
			return $this;
		}
		
		public function getChildren(): Collection
		{
			return $this->children;
		}
		
		public function addChild(Quack $child): static
		{
			if (!$this->children->contains($child)) {
				$this->children->add($child);
				$child->setParent($this); // Définir automatiquement le parent pour l'enfant
			}
			return $this;
		}
		
		public function removeChild(Quack $child): static
		{
			if ($this->children->removeElement($child)) {
				if ($child->getParent() === $this) {
					$child->setParent(null); // Retirer la relation parent pour l'enfant
				}
			}
			return $this;
		}
		
	
		
	}
