<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

#[Route('/auth')]
class AuthController extends BaseController
{
    private $JWTManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        JWTTokenManagerInterface $JWTManager
    ) {
        parent::__construct($entityManager, $logger);
        $this->JWTManager = $JWTManager;
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = base64_decode($data['password']) ?? null;

        // validate data
        if (!$username || !$email || !$password) {
            $this->logger->error('Error registering user: username, email and password are required');
            return $this->json(['message' => 'Username, email and password are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // check if user with the same email exists
        $userEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!empty($userEmail)) {
            $this->logger->error('Error registering user: email already exists');
            return $this->json(['message' => 'User with that email already exists'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // check if user with the same username exists
        $userUsername = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if (!empty($userUsername)) {
            $this->logger->error('Error registering user: username already exists');
            return $this->json(['message' => 'User with that username already exists'], JsonResponse::HTTP_BAD_REQUEST);
        }

       
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = $this->JWTManager->create($user);

        return $this->json(['message' => 'User registered!'], JsonResponse::HTTP_OK, ['Authorization' => $token]);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $password = base64_decode($data['password']) ?? null;

        // validate data
        if (!$username || !$password) {
            $this->logger->error('Error logging in: username and password are required');
            return $this->json(['message' => 'Username and password are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // check if user exists
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        
        // check if password is correct
        if (!$user || !password_verify($password,$user->getPassword())) {
            $this->logger->error('Error logging in: invalid username or password');
            return $this->json(['message' => 'Invalid username or password'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->JWTManager->create($user);

        return $this->json(['token' => $token], JsonResponse::HTTP_OK, ['Authorization' => $token]);
    }

    #[Route('/refresh', name: 'refresh', methods: ['POST'])]
    public function refresh(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $password = base64_decode($data['password']) ?? null;

        // validate data
        if (!$username || !$password) {
            $this->logger->error('Error refreshing token: username and password are required');
            return $this->json(['message' => 'Username and password are required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // check if user exists
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        // check if password is correct
        if (!$user || !password_verify($password,$user->getPassword())) {
            $this->logger->error('Error refreshing token: invalid username or password');
            return $this->json(['message' => 'Invalid username or password'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->JWTManager->create($user);

        return $this->json(['token' => $token], JsonResponse::HTTP_OK, ['Authorization' => $token]);
    }
}