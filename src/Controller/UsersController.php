<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Route;
use App\Entity\User;

class UsersController extends AbstractController
{
    /**
     * @Route\Get("/users", name="get_all_users")
     *
     * Метод получения списка пользователей
     *
     * @return string
     */
    public function getAllUsers()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $data = [];
        foreach ($users as $item) {
            $data[] = [
                'id' => $item->getId(),
                'first_name' => $item->getFirstName(),
                'last_name' => $item->getLastName()
            ];
        }

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Get("/users/{id}", name="get_item_users")
     *
     * Метод получения пользователя по id
     *
     * @param integer $id - id пользователя
     * @return string
     */
    public function getItemUsers($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'No user found for id ' . $id
            ];

            return new JsonResponse($data, 404);
        }

        $data = [
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Post("/users", name="add_users")
     *
     * Метод создания нового пользователя
     *
     * @param object $validator - валидатор
     * @param object $request - объект запроса
     * @return string
     */
    public function addUsers(ValidatorInterface $validator, Request $request)
    {
        $user = new User();
        $user->setFirstName($request->get('first_name'));
        $user->setLastName($request->get('last_name'));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $data = [
                'status' => 422,
                'message' => 'User no added',
                'errors' => (string) $errors
            ];

            return new JsonResponse($data, 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'User added'
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Put("/users/{id}", name="update_users")
     *
     * Метод обновления пользователя
     *
     * @param integer $id - id пользователя
     * @param object $validator - валидатор
     * @param object $request - объект запроса
     * @return string
     */
    public function updateUsers($id, ValidatorInterface $validator, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'No user found for id ' . $id
            ];

            return new JsonResponse($data, 404);
        }

        $user->setFirstName($request->get('first_name'));
        $user->setLastName($request->get('last_name'));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $data = [
                'status' => 422,
                'message' => 'User no updated',
                'errors' => (string) $errors
            ];

            return new JsonResponse($data, 422);
        }

        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'User updated'
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route\Delete("/users/{id}", name="delete_users")
     *
     * Метод удаления пользователя
     *
     * @param integer $id - id пользователя
     * @return string
     */
    public function deleteUsers($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            $data = [
                'status' => 404,
                'message' => 'No user found for id ' . $id
            ];

            return new JsonResponse($data, 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'User deleted'
        ];

        return new JsonResponse($data, 200);
    }
}
