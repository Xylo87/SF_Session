<?php

namespace App\Controller;

use DateTime;
use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
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
    public function show(Session $session = null, SessionRepository $sessionRepository): Response 
    {
        
        $nonInscrits = $sessionRepository->findNonInscrits($session->getId());
        $nonProgs = $sessionRepository->findNonProg($session->getId());
        
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
            'nonProgs' => $nonProgs
        ]);
    }

    #[Route('/session/{session}/programme/{module}/prog', name: 'prog_module')]
    public function prog_Module(Session $session, Module $module, Request $request, EntityManagerInterface $entityManager) {

        $programme = new Programme();
        $nbJours = $request->get('nbJours');
        $programme->setNbJoursModule($nbJours);
        $programme->setModule($module);
        $programme->setSession($session);

        $entityManager->persist($programme);
        $entityManager->flush();

        $this->addFlash('moSeAddSuccess', 'Le module "'.$programme->getModule().'" a bien été ajouté à la session "'.$session->getNom().'" ! ');
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{session}/programme/{programme}/deprog', name: 'deprog_module')]
    public function deprog_Module(Session $session, Programme $programme, EntityManagerInterface $entityManager) {
        
        $entityManager->remove($programme);
        $entityManager->flush();
        
        $this->addFlash('moSeDelSuccess', 'Le module "'.$programme->getModule().'" a bien été retiré de la session "'.$session->getNom().'" ! ');
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{session}/stagiaire/{stagiaire}/add', name: 'add_stagiaire')]
    public function add_Stagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager)
    {
    
        $session->addStagiaire($stagiaire);
    
        $entityManager->persist($session);
        $entityManager->flush();
    
        $this->addFlash('stSeAddSuccess', 'Le stagiaire "'.$stagiaire->getPrenom().' '.$stagiaire->getNom().'" a bien été ajouté à la session "'.$session->getNom().'" ! ');
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{session}/stagiaire/{stagiaire}/remove', name: 'remove_stagiaire')]
    public function remove_Stagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager)
    {
    
        $session->removeStagiaire($stagiaire);
    
        $entityManager->persist($session);
        $entityManager->flush();
    
        $this->addFlash('stSeDelSuccess', 'Le stagiaire "'.$stagiaire->getPrenom().' '.$stagiaire->getNom().'" a bien été retiré de la session "'.$session->getNom().'" ! ');
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
}
