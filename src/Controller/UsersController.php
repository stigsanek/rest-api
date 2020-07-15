<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as RestRoute;

class UsersController extends AbstractController
{
    /**
     * @RestRoute\Get("/users", name="get_all_users")
     */
    public function getAllUsers()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php',
        ]);
    }

    /**
     * @RestRoute\Get("/users/{id}", name="get_item_users")
     */
    public function getItemUsers($id)
    {

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
