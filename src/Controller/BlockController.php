<?php

namespace App\Controller;

use App\Entity\Block;
use App\Service\BlockService;
use App\Entity\User;
use App\Form\BlockType;
use App\Repository\BlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Provider\UserProvider;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/block')]
final class BlockController extends AbstractController
{
    #[Route('/new', name: 'app_block_new', methods: ['PUT', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider,
        BlockService $blockService): Response
    {

        $block = new Block();

        $user = $userProvider->getCurrentUser();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);
        
        if (isset($data['names']) && is_array($data['names'])) {
            $data['names'] = array_map(fn($n) => $n['value'] ?? '', $data['names']);
        }

        $form->submit($data);
        
        if($csrfTokenManager->isTokenValid(new CsrfToken('block_form', $data['_csrf_token']))) {
            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }
                
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $block->setUser($user);
            $block->setPosition($blockService->getNextBlockPosition($user)['next_position']);
            $entityManager->persist($block);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Création réussie',
            'block' => [
                'id' => $block->getId(),
                'names' => $block->getNames(),
                'title' => $block->getTitle(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'app_block_edit', methods: ['PUT', 'POST'])]
    public function edit(
        Request $request, 
        Block $block, 
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response
    {
        $user = $userProvider->getCurrentUser();

        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);

        if (isset($data['names']) && is_array($data['names'])) {
            $data['names'] = array_map(fn($n) => $n['value'] ?? '', $data['names']);
        }

        $form->submit($data);

        if($csrfTokenManager->isTokenValid(new CsrfToken('block_form', $data['_csrf_token']))) {
            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $block->setUser($user);
            $entityManager->persist($block);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Édition réussie',
            'block' => [
                'id' => $block->getId(),
                'names' => $block->getNames(),
                'title' => $block->getTitle(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_block_delete', methods: ['DELETE'])]
    public function delete(
        Request $request,
        Block $block,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response 
    {
        $token = $request->headers->get('X-CSRF-TOKEN');
        $data = json_decode($request->getContent(), true);

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('block_form', $data['_csrf_token']))) {
            return $this->json([
                'message' => 'Jeton CSRF invalide.',
            ], Response::HTTP_FORBIDDEN);
        }

        $user = $userProvider->getCurrentUser();
        if (count($user->getBlocks()) <= 1) {
            return $this->json([
                'message' => 'Impossible de supprimer le dernier Bloc.',
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $entityManager->remove($block);
        $entityManager->flush();

        return $this->json([
            'message' => 'Suppression réussie',
        ], Response::HTTP_NO_CONTENT);
    }

    #[Route('/reorder', name: 'app_block_reorder', methods: ['POST'])]
    public function reorder(Request $request, BlockRepository $blockRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $order = json_decode($request->getContent(), true);

        foreach ($order as $item) {
            $block = $blockRepository->find($item['id']);
            if ($block) {
                $block->setPosition($item['position']);
            }
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'ok']);
    }
}