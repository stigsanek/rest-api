<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Route;
use App\Entity\Task;

class TasksController extends AbstractController
{
    /**
     * @Route\Get("/tasks", name="get_all_tasks")
     *
     * Метод получения списка задач
     *
     * @return object
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

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Get("/tasks/{id}", name="get_item_tasks")
     *
     * Метод получения задачи по id
     *
     * @param integer $id - id задачи
     * @return object
     */
    public function getItemTasks($id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task) {
            $data = [
                'status' => 404,
                'message' => 'No task found for id ' . $id
            ];

            return new JsonResponse($data, 404);
        }

        $data = [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'deadline' => $task->getDeadline(),
            'user_id' => $task->getUserId()
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Post("/tasks", name="add_tasks")
     *
     * Метод создания новой задачи
     *
     * @param object $validator - валидатор
     * @param object $request - объект запроса
     * @return object
     */
    public function addTasks(ValidatorInterface $validator, Request $request)
    {
        $task = new Task();
        $task->setTitle($request->get('title'));
        $task->setDeadline(new \DateTime($request->get('deadline')));
        $task->setUserId($request->get('user_id'));

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            $data = [
                'status' => 422,
                'message' => 'Task no added',
                'errors' => (string) $errors
            ];

            return new JsonResponse($data, 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'Task added'
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Put("/tasks/{id}", name="update_tasks")
     *
     * Метод обновления задачи
     *
     * @param integer $id - id задачи
     * @param object $validator - валидатор
     * @param object $request - объект запроса
     * @return object
     */
    public function updateTasks($id, ValidatorInterface $validator, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            $data = [
                'status' => 404,
                'message' => 'No task found for id ' . $id
            ];

            return new JsonResponse($data, 404);
        }

        $task->setTitle($request->get('title'));
        $task->setDeadline(new \DateTime($request->get('deadline')));
        $task->setUserId($request->get('user_id'));

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            $data = [
                'status' => 422,
                'message' => 'Task no updated',
                'errors' => (string) $errors
            ];

            return new JsonResponse($data, 422);
        }

        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'Task updated'
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Delete("/tasks/{id}", name="delete_tasks")
     *
     * Метод удаления задачи
     *
     * @param integer $id - id задачи
     * @return object
     */
    public function deleteTasks($id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task) {
            $data = [
                'status' => 404,
                'message' => 'No task found for id ' . $id
            ];

            return new JsonResponse($data, 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'Task deleted'
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Get("/filter", name="get_by_filter")
     *
     * Метод фильтрации списка задач
     *
     * @param object $request - объект запроса
     * @return object
     */
    public function getByFilter(Request $request)
    {
        try {
            $tasks = $this->getDoctrine()
                ->getRepository(Task::class)
                ->findByFilter($request);

            if (empty($tasks)) {
                $data = [
                    'status' => 404,
                    'message' => 'No task found'
                ];

                return new JsonResponse($data, 404);
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

            return new JsonResponse($filters, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 404,
                'message' => 'No task found'
            ];

            return new JsonResponse($data, 404);
        }
    }
}
