<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

use App\Entity\Utilisateur;

class GetUtilisateurController extends AbstractController
{
    /**
     * @Route("/api/user/utilisateur", name="get_utilisateur", methods={"GET"})
     */
    public function index()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer($classMetadataFactory)];
        $serializer = new Serializer($normalizer, $encoder);

        $user = $this->getUser();
        if ($user === null || !$user instanceof Utilisateur) {
            throw new Exception("Utilisateur non trouvé");
        }

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => ['utilisateur:get']]);

      return new Response($jsonContent);

    }
}
