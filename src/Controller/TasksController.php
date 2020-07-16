<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Route;
use App\Entity\Task;

class TasksController extends AbstractController
{
    /**
     * @Route\Get("/tasks", name="get_all_tasks")
     *
     * Метод получения списка задач
     */
    public function getAllTasks()
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        $data = [];
        foreach ($tasks as $item) {
            $data[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'deadline' => $item->getDeadline(),
                'user_id' => $item->getUserId()
            ];
        }

        return $this->json([
            'tasks' => $data
        ]);
    }

    /**
     * @Route\Get("/tasks/{id}", name="get_item_tasks")
     *
     * Метод получения задачи по id
     */
    public function getItemTasks($id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task) {
            return $this->json([
                'message' => 'No task found for id ' . $id,
                'code' => 400
            ]);
        }

        return $this->json([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'deadline' => $task->getDeadline(),
            'user_id' => $task->getUserId()
        ]);
    }
}
