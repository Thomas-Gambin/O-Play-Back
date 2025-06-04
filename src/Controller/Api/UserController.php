<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/user', name: 'app_api_user_')]
class UserController extends AbstractController
{
    #[Route('/detail', name: 'show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], 401);
        }

        /** @var User $user */

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user_show']);
    }

    #[Route('/edit', name: 'edit', methods: ['PATCH'])]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], 401);
        }

        /** @var User $user */

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'JSON invalide'], 400);
        }

        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }
        if (isset($data['lastname'])) {
            $user->setLastname($data['lastname']);
        }
        if (isset($data['nickname'])) {
            $user->setNickname($data['nickname']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['picture'])) {
            $user->setPicture($data['picture']);
        }
        if (isset($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur mis à jour avec succès'], 200);
    }

    #[Route('/tags', name: 'update_tags', methods: ['POST'])]
    public function updateTags(
        Request $request,
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], 401);
        }

        /** @var User $user */

        $data = json_decode($request->getContent(), true);
        if (!isset($data['tag_ids']) || !is_array($data['tag_ids'])) {
            return new JsonResponse(['error' => 'Liste des IDs de tags manquante ou incorrecte'], 400);
        }

        foreach ($user->getPreferedTag() as $tag) {
            $user->removePreferedTag($tag);
        }

        foreach ($data['tag_ids'] as $tagId) {
            $tag = $tagRepository->find($tagId);
            if ($tag) {
                $user->addPreferedTag($tag);
            } else {
                return new JsonResponse(['error' => "Tag avec ID $tagId non trouvé"], 404);
            }
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Tags mis à jour avec succès'], 200);
    }

    #[Route('/categories', name: 'update_category', methods: ['POST'])]
    public function updateCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], 401);
        }

        /** @var User $user */

        $data = json_decode($request->getContent(), true);
        if (!isset($data['category_ids']) || !is_array($data['category_ids'])) {
            return new JsonResponse(['error' => 'Liste des IDs de catégories manquante ou incorrecte'], 400);
        }

        foreach ($user->getSelectedCategory() as $category) {
            $user->removeSelectedCategory($category);
        }

        foreach ($data['category_ids'] as $categoryId) {
            $category = $categoryRepository->find($categoryId);
            if ($category) {
                $user->addSelectedCategory($category);
            } else {
                return new JsonResponse(['error' => "Catégorie avec ID $categoryId non trouvée"], 404);
            }
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégories mises à jour avec succès'], 200);
    }

    #[Route('/theme', name: 'update_theme', methods: ['POST'])]
    public function updateTheme(
        Request $request,
        EntityManagerInterface $entityManager,
        ThemeRepository $themeRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié'], 401);
        }

        /** @var User $user */

        $data = json_decode($request->getContent(), true);
        if (!isset($data['theme_id'])) {
            return new JsonResponse(['error' => 'ID de thème manquant'], 400);
        }

        $theme = $themeRepository->find($data['theme_id']);
        if (!$theme) {
            return new JsonResponse(['error' => "Thème avec l'ID {$data['theme_id']} non trouvé"], 404);
        }

        $user->setChooseTheme($theme);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Thème mis à jour avec succès'], 200);
    }
}
