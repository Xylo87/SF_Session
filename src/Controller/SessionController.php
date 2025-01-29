<?php

namespace App\Controller;

use DateTime;
use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Repository\FormationRepository;
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

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session): Response 
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
