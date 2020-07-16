<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return Task[]
     *
     * Метод фильтрации списка задач
     */
    public function findByFilter($filterData)
    {
        if (empty($filterData)) {
            return;
        }

        $genQuery = '';
        $params = [];

        foreach ($filterData as $key => $value) {
            if ($key !== 'title' && $key !== 'deadline' && $key !== 'user_id') {
                return;
            }

            $genQuery .= 'task.' . $key . ' = :' . $key . ' AND ';
            $params[$key] = $value;
        }

        $where = substr($genQuery, 0, strlen($genQuery) - strlen(' AND '));

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT task FROM App\Entity\Task task WHERE ' . $where
        );

        foreach ($params as $item => $value) {
            $query->setParameter($item, $value);
        }

        return $query->getResult();
    }
}
