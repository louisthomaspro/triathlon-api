<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use App\Entity\Users;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthenticationListener extends AbstractController implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvent::class => ['onAuthenticationSuccessResponse'],
        ];
    }


    /**
     * 
     * Deprecated
     * 
     * The Events::AUTHENTICATION_SUCCESS event is not dispatched when creating JWT tokens programmatically (this is the case)
     * 
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof Users) {
            return;
        }

        $data['data'] = array(
            'id' => $user->getId(),
            'roles' => $user->getRoles(),
            'email' => $user->getEmail()
        );

        $event->setData($data);
    }



    public function onJWTCreated(JWTCreatedEvent $event)
    {

        $payload = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof Users) {
            return;
        }

        $em = $this->getDoctrine()->getManager();
        $userData = $em->getRepository(Users::class)->findOneBy(['email'=>$user->getEmail()],[]);

        unset($payload['username']);

        $payload['email'] = $userData->getEmail();

        $event->setData($payload);

        // $header        = $event->getHeader();
        // $header['cty'] = 'JWT';

        // $event->setHeader($header);
    }
}
