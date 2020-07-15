<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as RestRoute;
use App\Entity\User;

class UsersController extends AbstractController
{
    /**
     * @RestRoute\Get("/users", name="get_all_users")
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
     * @RestRoute\Get("/users/{id}", name="get_item_users")
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
     * @RestRoute\Post("/users", name="add_users")
     */
    public function addUsers()
    {
    }

    /**
     * @RestRoute\Put("/users/{id}", name="update_users")
     */
    public function updateUsers($id)
    {
    }

    /**
     * @RestRoute\Delete("/users/{id}", name="delete_users")
     */
    public function deleteUsers($id)
    {
    }
}
