<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Route;
use App\Service\Extractor;
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

    /**
     * @Route\Post("/tasks", name="add_tasks")
     *
     * Метод создания новой задачи
     */
    public function addTasks(ValidatorInterface $validator)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = Extractor::extractData($method);

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDeadline(new \DateTime($data['deadline']));
        $task->setUserId($data['user_id']);

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Task no added',
                'code' => 400,
                'errors' => (string) $errors
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json([
            'message' => 'Task added',
            'code' => 200
        ]);
    }

    /**
     * @Route\Put("/tasks/{id}", name="update_tasks")
     *
     * Метод обновления задачи
     */
    public function updateTasks($id, ValidatorInterface $validator)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = Extractor::extractData($method);

        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->json([
                'message' => 'No task found for id ' . $id,
                'code' => 400
            ]);
        }

        $task->setTitle($data['title']);
        $task->setDeadline(new \DateTime($data['deadline']));
        $task->setUserId($data['user_id']);

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Task no updated',
                'code' => 400,
                'errors' => (string) $errors
            ]);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Task updated',
            'code' => 200
        ]);
    }

    /**
     * @Route\Delete("/tasks/{id}", name="delete_tasks")
     *
     * Метод удаления задачи
     */
    public function deleteTasks($id)
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

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json([
            'message' => 'Task deleted',
            'code' => 200
        ]);
    }

    /**
     * @Route\Get("/filter", name="get_by_filter")
     *
     * Метод фильтрации списка задач
     */
    public function getByFilter()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = Extractor::extractData($method);

        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findByFilter($data);

        if (empty($tasks)) {
            return $this->json([
                'message' => 'No task found',
                'code' => 400
            ]);
        }

        $filters = [];
        foreach ($tasks as $item) {
            $filters[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'deadline' => $item->getDeadline(),
                'user_id' => $item->getUserId()
            ];
        }

        return $this->json([
            'count' => count($filters),
            'tasks' => $filters
        ]);
    }
}
