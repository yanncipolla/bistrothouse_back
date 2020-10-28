<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Entity\Utilisateur;

class GetUtilisateurController extends AbstractController
{
    /**
     * @Route("/api/user/utilisateur", name="get_utilisateur", methods={"GET"})
     */
    public function index()
    {
        // Objets necessaires à la création du serializer JSON
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();
        if ($user === null || !$user instanceof Utilisateur) {
            throw new Exception("Utilisateur non trouvé");
        }

        $user->setPassword("");

        $jsonContent = $serializer->serialize($user, 'json');

        return new \Symfony\Component\HttpFoundation\Response($jsonContent);

    }
}
