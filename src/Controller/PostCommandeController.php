<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCde;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PostCommandeController extends AbstractController
{
    /**
     * @Route("/api/user/commande", name="post_commande", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer)
    {
        if (!$data = $request->getContent()){
            throw new Exception("Saisie incorrecte, requete non interpretable");
        }
        $json = json_decode($data, true);

        $user = $this->getUser();
        if ($user === null || !$user instanceof Utilisateur) {
            throw new Exception("Utilisateur non trouvé");
        }

        $listeProduitsHTML = "";
        $prixTotal = 0;

        $commande = new Commande();
        $commande->setEmporter($json["emporter"]);
        $commande->setDateRetrait(new \DateTime());
        $commande->setCommentaire($json["commentaire"]);
        $commande->setUtilisateur($user);


        $repoProduit = $this->getDoctrine()->getRepository(Produit::class);
        for ($i=0; $i < count($json["ligneCommande"]); $i++){
            $ligne = new LigneCde();
            if (!$produit = $repoProduit->find($json["ligneCommande"][$i][0])){
                throw new Exception("Produit non trouvé");
            }
            $ligne->setProduit($produit);
            $ligne->setQuantite($json["ligneCommande"][$i][1]);
            $ligne->setCommande($commande);
            $em->persist($ligne);
            $prixTotal = $prixTotal + $produit->getPrix() * $ligne->getQuantite();
            $listeProduitsHTML .= $produit->getNom() . ". Quantité : " . $ligne->getQuantite() . "<BR>";
        }

        $commande->setPrixTotal($prixTotal);
        $em->persist($commande);

        $em->flush();

        $email = (new Email())
            ->from('bistrothouse@gmail.com')
            ->to($_ENV['MAIL_COMMANDE']);
        if ($commande->getEmporter()){
            $livraison = "a emporter";
        } else {
            $livraison = "a livrer";
        }
        $email->subject("Nouvelle Commande " . $livraison);
        $email->html(
            '<p>Vous avez recu une nouvelle commande ' . $livraison . ' : ' . $commande->getId() . '</p>' .
            '<br>' .
            '<p>Détail du client :</p>' .
            '<p>' . $user->getNom() . ' ' . $user->getPrenom() . '<br>' .
            $user->getNumEtRue() . '<br>' .
            $user->getCp() . ' ' . $user->getVille() . '<br>' .
            'Complement d\'adresse : ' . $user->getComplement() . '<BR>' .
            'Téléphone : ' . $user->getTelephone() . '</p>' .
            '<br>' .
            '<p>Liste des produits commandés : </p>' .
            $listeProduitsHTML.
            '<br>' .
            'Commentaire commande : ' . $commande->getCommentaire() .
            '<br>' .
            '<p>Prix total : ' . $prixTotal . '€</p>' .
            '<p>Fin de commande</p>'
        );
        $mailer->send($email);

        $emailClient = (new Email())
            ->from('bistrothouse@gmail.com')
            ->to($user->getEmail());
        if ($commande->getEmporter()){
            $livraison = "a emporter";
        } else {
            $livraison = "a livrer";
        }
        $emailClient->subject("Votre commande " . $livraison . " a été validée et est en cours de préparation.");
        $emailClient->html(
            '<p>Bonjour ' . $user->getPrenom() . ',</p>' .
            '<br>' .
            '<p>Nous avons bien recu votre commande et celle-ci est en cours de préparation. Vous trouverez son détail ci-dessous.</p>' .
            '<p>Liste des produits commandés : </p>' .
            $listeProduitsHTML.
            '<p>Commentaire commande : ' . $commande->getCommentaire() . '</p>' .
            '<p>Prix total : ' . $prixTotal . '€</p>' .
            '<p>Merci pour votre commande et à très bientot dans notre restaurant.</p>' .
            '<br>' .
            '<p>L\'équipe Bistrot-House</p>'
        );
        $mailer->send($emailClient);

        return new JsonResponse(['message' => 'Commande enregistree']);
    }
}
