<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurEventSubscriber implements EventSubscriberInterface{

  /**
   * @var UserPasswordEncoderInterface
   */
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder){
    $this->passwordEncoder = $passwordEncoder;
  }

  public function getSubscribedEvents(){
    return [KernelEvents::VIEW => ['encodePassword', EventPriorities::POST_WRITE]];
  }

  public function encodePassword(ViewEvent $event){
    if ($event->getControllerResult() instanceof Utilisateur && $event->getRequest()->getMethod() === "POST"){
      $utilisateur = $event->getControllerResult();
      $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
    }
  }

}