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
        UserProvider $userProvider): Response
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
            $entityManager->persist($block);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Création réussie',
        ], Response::HTTP_CREATED);
    }
}