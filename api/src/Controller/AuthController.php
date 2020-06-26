<?php

namespace App\Controller;

use App\DTO\RegistrationRequest;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends AbstractController
{
    /** @var UserService */
    private $userService;

    /** @var JWTTokenManagerInterface */
    private $jwtManager;

    public function __construct(UserService $userService, JWTTokenManagerInterface $jwtManager)
    {
        $this->userService = $userService;
        $this->jwtManager = $jwtManager;
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $user = $this->userService->createUserFromRequest($request);
        return new JsonResponse(['success' => true, 'token' => $this->jwtManager->create($user)]);
    }
}
