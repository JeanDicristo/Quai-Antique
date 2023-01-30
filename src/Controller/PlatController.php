<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/plat', name: 'plat_index', methods: ['GET'])]
    public function index(
        PlatRepository $repository, 
        PaginatorInterface $paginator, 
        Request $request
        ): Response
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
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {

        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $plat = $form->getData();

            $manager->persist($plat);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre plat a été créer avec succès !'
            );
        } else {
            $this->addFlash(
                'warning',
                'Votre plat n\'a pas été créer !'
            );
        }

        return $this->render('pages/plat/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
