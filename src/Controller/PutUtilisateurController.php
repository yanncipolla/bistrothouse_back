<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adresse;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PutUtilisateurController extends AbstractController
{
  const ERREUR_EMAIL_EXISTANT = 409;
  const ERREUR_MDP_INCORRECT = 422;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
    $this->passwordEncoder = $passwordEncoder;
  }

  /**
   * @Route("/api/user/utilisateur", name="put_utilisateur", methods={"PUT"})
   * @param Utilisateur $data
   * @param UtilisateurRepository $utilisateurRepo
   * @return JsonResponse
   */
    public function index(Request $data, UtilisateurRepository $utilisateurRepo)
    {
      if ( ! $data = $data->getContent()) {
        throw new Exception("Saisie incorrecte, requete non interpretable");
      }

      $encoders = [new JsonEncoder()];
      $normalizers = [new ObjectNormalizer()];
      $serializer = new Serializer($normalizers, $encoders);
      $userSaisi = $serializer->deserialize($data, Utilisateur::class, 'json', ['attributes' => ['email', 'password', 'nom', 'prenom', 'complement', 'telephone',
        'numEtRue', 'ville', 'cp']]);
      if ($userSaisi === null || !$userSaisi instanceof Utilisateur) {
        throw new Exception("Saisie incorrecte, probleme lors de la deserialisation");
      }

      $entityManager = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      if ($user === null || !$user instanceof Utilisateur) {
        throw new Exception("Utilisateur non trouvé");
      }

      if ($userSaisi->getEmail() != "" && $userSaisi->getEmail() != $user->getEmail()){
        if ($utilisateur = $utilisateurRepo->findBy(['email' => $userSaisi->getEmail()])){
          return new JsonResponse(['message' => "Modification impossible : L'adresse email existe déjà."], self::ERREUR_EMAIL_EXISTANT);
        } else {
          $user->setEmail($userSaisi->getEmail());
        }
      }

      if ($userSaisi->getPrenom() != ""){$user->setPrenom($userSaisi->getPrenom());}
      if ($userSaisi->getNom() != ""){$user->setNom($userSaisi->getNom());}
      if ($userSaisi->getComplement() != ""){$user->setComplement($userSaisi->getComplement());}
      if ($userSaisi->getTelephone() != ""){$user->setTelephone($userSaisi->getTelephone());}
      if ($userSaisi->getNumEtRue() != ""){$user->setNumEtRue($userSaisi->getNumEtRue());}
      if ($userSaisi->getVille() != ""){$user->setVille($userSaisi->getVille());}
      if ($userSaisi->getCp() != ""){$user->setCp($userSaisi->getCp());}

      // TODO implementer le check du password avant modification
      // for check password : https://symfonycasts.com/screencast/symfony-security/user-password
      if ($userSaisi->getPassword() != ""){
        $user->setPassword($this->passwordEncoder->encodePassword($user, $userSaisi->getPassword()));
      }

      $entityManager->persist($user);
      $entityManager->flush();

      return new JsonResponse(['reponse' => 'Utilisateur modifie']);
    }
}
