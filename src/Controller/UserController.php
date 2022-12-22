<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users_list', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json([
            'data' => $userRepository->findAll()
        ]);
    }

    #[Route('/users', name:'users_create', methods: ['POST'])]
    public function create(Request $request, UserRepository $userRepository):JsonResponse
    {
        $data = $request->request->all();

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setCreatedAt();

        $userRepository->save($user, true);

        return $this->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    #[Route('/users/{userId}', name: 'users_update', methods: ['PUT', 'PATCH'])]
    public function update(int $userId, Request $request, UserRepository $userRepository):JsonResponse
    {
        $user = $userRepository->find($userId);
        if(!$user) throw $this->createNotFoundException();

        $data = $request->request->all();

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $userRepository->update($user, true);

        return $this->json([
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }
}
