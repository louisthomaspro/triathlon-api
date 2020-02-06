<?php

namespace App\Controller;

use App\Entity\Store;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\UsersRepository;
use App\Service\UserManager;
use Google_Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    /** @var UsersRepository $userRepository */
    private $usersRepository;
    private $userManager;
    private $encoder;

    /**
     * AuthController Constructor
     *
     * @param UsersRepository $usersRepository
     */
    public function __construct(UsersRepository $usersRepository, UserManager $userManager, UserPasswordEncoderInterface $encoder)
    {
        $this->usersRepository = $usersRepository;
        $this->userManager = $userManager;
        $this->encoder = $encoder;
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

        $this->denyAccessUnlessGranted('ROLE_STORE_MANAGER');


        $data = json_decode($request->getContent());

        $roles = ['SELLER'];
        if ($this->isGranted('ROLE_ADMIN')) {
            if (strcmp($data->role, 'ROLE_ADMIN') == 0) {
                $roles = ["ROLE_ADMIN", "ROLE_SELLER", "ROLE_STORE_MANAGER"];
            } else if (strcmp($data->role, 'ROLE_STORE_MANAGER') == 0) {
                $roles = ["ROLE_SELLER", "ROLE_STORE_MANAGER"];
            }
        }

        $store = $data->store;
        if (in_array("ROLE_ADMIN", $roles)) {
            $store = null;
        } else {
            $em = $this->getDoctrine()->getManager();
            $store = $em->getRepository(Store::class)->findOneBy(['id' => $store], []);
            if (is_null($store)) {
                throw new HttpException(203, "Store does not exist.");
            }
        }

        $newUserData['email']    = $data->email;
        $newUserData['password'] = $data->password;
        $newUserData['store'] = $store;
        $newUserData['roles'] = $roles;

        $user = $this->usersRepository->createNewUser($newUserData);

        $reponseData = ['message' => "User " . $user->getEmail() . " successfully created"];
        return $this->json($reponseData, 200);
    }



    public function login(Request $request)
    {
        $data = json_decode($request->getContent());
        $realUser = $this->usersRepository->findOneBy(['email'=>$data->email],[]);

        // Check if the user exists !
        if(!$realUser){
            return new Response(
                'Username doesnt exists',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }

        if(!$this->encoder->isPasswordValid($realUser, $data->password)) {
            return new Response(
                'Username or Password not valid.',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }

        $user = new Users();
        $user->setEmail($data->email);

        return $this->userManager->connectUser($user);
    }
}
