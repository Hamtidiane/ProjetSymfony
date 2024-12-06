<?php
	
	namespace App\Entity;
	
	use Doctrine\ORM\Mapping as ORM;
	
	#[ORM\Entity]
	class Reply
	{
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;
		
		#[ORM\Column(type: 'text')]
		private ?string $content = null;
		#[ORM\Column(type: 'datetime_immutable')]
		private ?\DateTimeImmutable $createdAt = null;
		
		#[ORM\ManyToOne(targetEntity: Quack::class, inversedBy: 'replies')]
		#[ORM\JoinColumn(nullable: false)]
		private ?Quack $quack = null;
		
		#[ORM\ManyToOne(targetEntity: Ducks::class, inversedBy: 'replies')]
		#[ORM\JoinColumn(nullable: false)]
		private ?Ducks $author = null;
		
		
		public function getQuack(): ?Quack
		{
			return $this->quack;
		}
		
		public function setQuack(Quack $quack): self
		{
			$this->quack = $quack;
			return $this;
		}
		
		public function getContent(): ?string
		{
			return $this->content;
		}
		
		public function setContent(string $content): self
		{
			$this->content = $content;
			return $this;
		}
		
		public function getAuthor(): ?Ducks
		{
			return $this->author;
		}
		
		public function setAuthor(?Ducks $author): self
		{
			$this->author = $author;
			return $this;
		}
		
		public function getCreatedAt(): ?\DateTimeImmutable
		{
			return $this->createdAt;
		}
		
		public function setCreatedAt(\DateTimeImmutable $createdAt): self
		{
			$this->createdAt = $createdAt;
			return $this;
		}
		
		// src/Entity/Reply.php
		
		public function __construct()
		{
			$this->createdAt = new \DateTimeImmutable(); // Assure que la date de création est définie à la création de l'entité
		}
		
	}
