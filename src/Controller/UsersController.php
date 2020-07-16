<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Route;
use App\Service\Extractor;
use App\Entity\User;

class UsersController extends AbstractController
{
    /**
     * @Route\Get("/users", name="get_all_users")
     *
     * Метод получения списка пользователей
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

        return $this->json([
            'users' => $data
        ]);
    }

    /**
     * @Route\Get("/users/{id}", name="get_item_users")
     *
     * Метод получения пользователя по id
     */
    public function getItemUsers($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'No user found for id ' . $id
            ]);
        }

        return $this->json([
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
        ]);
    }

    /**
     * @Route\Post("/users", name="add_users")
     *
     * Метод добавления нового пользователя
     */
    public function addUsers(ValidatorInterface $validator)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = Extractor::extractData($method);

        $user = new User();
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'message' => 'User no added',
                'errors' => (string) $errors
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User added'
        ]);
    }

    /**
     * @Route\Put("/users/{id}", name="update_users")
     */
    public function updateUsers($id, ValidatorInterface $validator)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data = Extractor::extractData($method);

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'No user found for id ' . $id
            ]);
        }

        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'message' => 'User no updated',
                'errors' => (string) $errors
            ]);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'User updated'
        ]);
    }

    /**
     * @Route\Delete("/users/{id}", name="delete_users")
     */
    public function deleteUsers($id)
    {
    }
}
