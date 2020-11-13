<?php

namespace App\Controller\Api;

use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PostUtilisatateurController{

    const ERREUR_EMAIL_EXISTANT = 409;

  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(Utilisateur $data, UtilisateurRepository $utilisateurRepo){

        $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPassword()));

        return $data;
    }

}