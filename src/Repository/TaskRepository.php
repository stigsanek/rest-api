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
     * Метод фильтрации списка задач
     *
     * @param object $request - объект запроса
     * @return array
     */
    public function findByFilter($request)
    {
        $filterData = [];

        if ($request->get('deadline')) {
            $filterData['deadline'] = $request->get('deadline');
        }

        if ($request->get('user_id')) {
            $filterData['user_id'] = $request->get('user_id');
        }

        $genQuery = '';
        $params = [];

        foreach ($filterData as $key => $value) {
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
