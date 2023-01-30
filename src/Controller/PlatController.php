<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatController extends AbstractController
{
    /**
     * This function display all plat
     *
     * @param PlatRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/plat', name: 'app_plat', methods: ['GET'])]
    public function index(PlatRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $plats = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/plat/index.html.twig', [
            'plats' => $plats

        ]);
    }
    #[Route('/nouveau', 'plat.new', methods: ['GET', 'POST'])]
    public function new(): Response
    {

        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);

        return $this->render('pages/plat/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
