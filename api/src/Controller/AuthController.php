<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\UsersRepository;
use App\Service\UserManager;
use Google_Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends AbstractController
{
    /** @var UsersRepository $userRepository */
    private $usersRepository;
    private $userManager;

    /**
     * AuthController Constructor
     *
     * @param UsersRepository $usersRepository
     */
    public function __construct(UsersRepository $usersRepository, UserManager $userManager)
    {
        $this->usersRepository = $usersRepository;
        $this->userManager = $userManager;
    }

    /**
     * Register new user
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(Request $request)
    {

        $this->denyAccessUnlessGranted('ADMIN');

        $data = json_decode($request->getContent());
        $newUserData['email']    = $data->email;
        $newUserData['password'] = $data->password;
        $newUserData['store'] = $data->store;

        $user = $this->usersRepository->createNewUser($newUserData);

        $reponseData = ['message' => "User ".$user->getEmail()." successfully created"];
        return $this->json($reponseData, 200);
    }

    public function login(Request $request)
    {
        $data = json_decode($request->getContent());

        $user = new Users();
        $user->setEmail($data->email);
        $user->setPassword($data->password);        

        return $this->userManager->connectUser($user);
    }
    

}