<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/tricks", name="tricks")
     */
    public function showTricks(): Response
    {
        return $this->render('admin/tricks.html.twig');
    }

    /**
     * @Route("/comments", name="comments")
     */
    public function showComments(): Response
    {
        return $this->render('admin/comments.html.twig');
    }
}
