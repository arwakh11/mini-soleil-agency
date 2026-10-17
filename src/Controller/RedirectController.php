<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class RedirectController extends AbstractController
{
    public function index(): Response
    {
        return $this->redirectToRoute('home', [
            '_locale' => 'en',
        ]);
    }
}

