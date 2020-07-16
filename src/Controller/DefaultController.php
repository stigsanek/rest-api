<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     *
     * Action главной страницы
     *
     * @return string
     */
    public function index()
    {
        return new Response('Welcome to the task tracker REST API');
    }
}
