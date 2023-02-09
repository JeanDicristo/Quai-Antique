<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatController extends AbstractController
{
    /**
     * This controller display all plat
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
    /**
     * This controller show a form which create an plat
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
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

    #[Route('/plat/edition/{id}', 'plat.edit', methods: ['GET', 'POST'])]
    public function edit(
        Plat $plat,
        Request $request,
        EntityManagerInterface $manager
     ) : Response
    {   
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $plat = $form->getData();

            $manager->persist($plat);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre plat a été modifié avec succès !'
            );
        } else {
            $this->addFlash(
                'warning',
                'Votre plat n\'a pas été créer !'
            );
            
        }

        return $this->render('pages/plat/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/plat/suppression/{id}', 'plat.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Plat $plat) : Response
    {
        if(!$plat) {
            $this->addFlash(
                'warning',
                'Votre plat n\a pas été trouvé !'
               );
        }
       $manager->remove($plat);
       $manager->flush();

       $this->addFlash(
        'success',
        'Votre plat a été supprimé avec succès !'
       );

       return $this->redirectToRoute('plat_index');
       
    }
}
