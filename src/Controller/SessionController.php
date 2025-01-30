<?php

namespace App\Controller;

use DateTime;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {

        $sessions = $sessionRepository->findBy([], ["dateDebut" => "ASC"]);
        $pastSessions = $sessionRepository->pastSessions();
        $currentSessions = $sessionRepository->currentSessions();
        $upComingSessions = $sessionRepository->upComingSessions();

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
            'sessions' => $sessions,
            'pastSessions' => $pastSessions,
            'currentSessions' => $currentSessions,
            'upComingSessions' => $upComingSessions,
        ]);
    }

    #[Route('/formation', name: 'app_formation')]
    public function indexForma(FormationRepository $formationRepository): Response
    {

        $formations = $formationRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('formation/index.html.twig', [
            'controller_name' => 'SessionController',
            'formations' => $formations
        ]);
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$session) {
            $session = new Session();
        }
        
        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $session = $form->getData();

            $entityManager->persist($session);
            $entityManager->flush();

            $this->addFlash('seAddEditSuccess', 'Session "'.$session->getNom().'" ajoutée/modifiée !');
            return $this->redirectToRoute('app_session');
        } else {
            // $this->addFlash('inputError', 'Erreur de saisie, veuillez réessayer...');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId()
        ]);
    }

    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($session);
        $entityManager->flush();

        $this->addFlash('seDeleteSuccess', 'Session "'.$session->getNom().'" supprimée !');
        return $this->redirectToRoute('app_session');
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session): Response 
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
