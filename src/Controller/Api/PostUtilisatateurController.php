<?php

namespace App\Controller\Api;

use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PostUtilisatateurController{

    const ERREUR_UTILISATEUR_EXISTANT = 409;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(Utilisateur $data, UtilisateurRepository $utilisateurRepo){


        if ($utilisateur =$utilisateurRepo->findBy(['email' => $data->getEmail()])){
            return new JsonResponse(['response' => 'Adresse email déjà existante'], self::ERREUR_UTILISATEUR_EXISTANT);
        }

        $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPassword()));

        return $data;
    }

}