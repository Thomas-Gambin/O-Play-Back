<?php

namespace App\Controller\Backoffice;

use App\Entity\Game;
use App\Entity\GameOrder;
use App\Entity\UserGameKey;
use App\Form\GameType;
use App\Repository\GameOrderRepository;
use App\Repository\GameRepository;
use App\Service\GameKeyService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/game')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('backoffice/game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->render('backoffice/game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de l'image
            $pictureFile = $form->get('pictures')->getData();

            if ($pictureFile) {
                // Supprimer l'ancienne image si elle existe
                if ($game->getPicture()) {
                    $pictureService->delete($game->getPicture());
                }

                // Ajouter la nouvelle image
                $relativePath = $pictureService->add($pictureFile, 'games');
                $game->setPicture($relativePath);
            }

            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/generate-key', name: 'app_game_generate_key_user', methods: ['GET','POST'])]
    public function generateGameKeyUser(UserGameKey $userGameKey, GameKeyService $gameKeyService): Response
    {
        $gameKeyService = $gameKeyService->generateNewKey($userGameKey);

        $user = $userGameKey->getUser();

        // Ajout d'un message flash pour afficher un message de succès
        $this->addFlash('success', 'Nouvelle clé générée avec succès !');

        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }
}
    

