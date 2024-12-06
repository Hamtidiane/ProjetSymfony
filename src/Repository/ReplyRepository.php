<?php
	
	
	namespace App\Repository;
	
	use App\Entity\Reply;
	use App\Entity\Quack;
	use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
	use Doctrine\Persistence\ManagerRegistry;
	
	class ReplyRepository extends ServiceEntityRepository
	{
		public function __construct(ManagerRegistry $registry)
		{
			parent::__construct($registry, Reply::class);
		}
		
		/**
		 * Trouver toutes les réponses pour un Quack donné
		 *
		 * @param Quack $quack
		 * @return Reply[] Retourne un tableau d'objets Reply
		 */
		public function findByQuack(Quack $quack)
		{
			return $this->createQueryBuilder('r')
				->andWhere('r.quack = :quack')
				->setParameter('quack', $quack)
				->orderBy('r.createdAt', 'DESC')
				->getQuery()
				->getResult();
		}
		
		/**
		 * Trouver une réponse par son ID
		 *
		 * @param int $id
		 * @return Reply|null Retourne une seule réponse ou null si elle n'existe pas
		 */
		public function findOneById(int $id): ?Reply
		{
			return $this->find($id);
		}
	}