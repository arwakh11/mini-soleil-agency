<?php

namespace App\Controller;

use App\Entity\QuoteRequest;
use App\Form\QuoteRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/contact', name: 'contact')]
    public function index(Request $request): Response
    {

        $QuoteRequest = new QuoteRequest();


        $form = $this->createForm(QuoteRequestType::class, $QuoteRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->manager->persist($QuoteRequest);
            $this->manager->flush();


            return $this->redirectToRoute('home');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
