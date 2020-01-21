<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\UsersRepository;

class AuthController extends AbstractController
{
    /** @var UsersRepository $userRepository */
    private $usersRepository;

    /**
     * AuthController Constructor
     *
     * @param UsersRepository $usersRepository
     */
    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
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

        $data = json_decode($request->getContent());
        $newUserData['email']    = $data->email;
        $newUserData['password'] = $data->password;

        $user = $this->usersRepository->createNewUser($newUserData);

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

}