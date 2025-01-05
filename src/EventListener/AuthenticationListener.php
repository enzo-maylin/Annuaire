<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class AuthenticationListener {

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $entityManager){}

    #[AsEventListener]
    public function LoginSuccessEvent(LoginSuccessEvent $loginSuccessEvent): void
    {
        $user = $loginSuccessEvent->getUser();
        $user->setLastSignin(new \DateTimeImmutable());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("success", 'Connexion réussie !');
    }

    #[AsEventListener]
    public function LoginFailureEvent(LoginFailureEvent $loginFailureEvent): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("error", 'Login et/ou mot de passe incorrect !');
    }

    #[AsEventListener]
    public function LogoutEvent(LogoutEvent $logoutEvent): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("success", 'Déconnexion réussie !');
    }

}