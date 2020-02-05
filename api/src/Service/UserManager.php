<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\Seller;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Manage users.
 *
 * Currently used to hash password.
 *
 * @package AppBundle\Service
 */
class UserManager extends AbstractController
{

    private $jwtManager;

    public function __construct(
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->jwtManager = $jwtManager;
    }


    public function connectUser(Users $user)
    {

        if ($user === null) {
            throw new HttpException(404, "You do not have any account! What the hell are you doing here ?");
        }

        $token = $this->jwtManager->create($user);

        $em = $this->getDoctrine()->getManager();
        $userData = $em->getRepository(Users::class)->findOneBy(['email'=>$user->getEmail()],[]);
        $store = $userData->getStore();


        $data['id'] = $userData->getId();
        $data['email'] = $userData->getEmail();
        $data['roles'] = $userData->getRoles();
        $data['store'] = $store->getId();
        $responseData = ['token' => $token, 'data' => $data];

        return $this->json($responseData, 200);
    }
}
