<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaires = $stagiaireRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
            'stagiaires' => $stagiaires
        ]);
    }

    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function new_edit(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$stagiaire) {
            $stagiaire = new Stagiaire();
        }
        
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $stagiaire = $form->getData();

            $entityManager->persist($stagiaire);
            $entityManager->flush();

            $this->addFlash('stAddEditSuccess', 'Stagiaire "'.$stagiaire->getPrenom().' '.$stagiaire->getNom().'" ajouté.e/modifié.e !');
            return $this->redirectToRoute('app_stagiaire');
        } else {
            // $this->addFlash('inputError', 'Erreur de saisie, veuillez réessayer...');
        }


        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form,
            'edit' => $stagiaire->getId()
        ]);
    }

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        $this->addFlash('stDeleteSuccess', 'Stagiaire "'.$stagiaire->getPrenom().' '.$stagiaire->getNom().'" supprimé.e !');
        return $this->redirectToRoute('app_stagiaire');
    }

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]

    public function show(Stagiaire $stagiaire = null): Response 
    {
        if (!$stagiaire) {
            $this->addFlash('stSearchFail', 'Le stagiaire que vous cherchez n\'existe pas !');
            return $this->redirectToRoute('app_stagiaire');
        }

        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }
}
