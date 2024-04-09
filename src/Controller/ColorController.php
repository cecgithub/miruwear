<?php

namespace App\Controller;

use App\Entity\Color;
use App\Form\ColorType;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')] 
#[Route('/admin/color')]
class ColorController extends AbstractController
{
    #[Route('/', name: 'app_color')] // insert, ajoute la couleur
    #[Route('/{id}', name: 'app_color_update')] // update, on modifie la couleur par son id
    public function index(ColorRepository $repository, EntityManagerInterface $manager, Request $request, int $id=null): Response
    {
        // le repository sert à faire un SELECT 
        // EntityManagerInterface sert à faire les INSERT INTO, UPDATE, DELETE
        // Request permet de réccupérer les informations dans le formulaire

        if ($id) {
            $color = $repository->find($id); // on cherche la couleur correspondante à l'id dans l'url
        } else {
            $color = new Color(); // ici, on appelle l'Entity afin d'insérer une couleur dans la base de donnée
        }

        $colors = $repository->findAll(); // ici on réccupère toutes les couleurs dans la base de données

        $form = $this->createForm(ColorType::class, $color);
        // On appelle le formulaire ColorType, on passe en deuxieme paramètre l'objet $color. 
        // Sans $id, l'objet Color étant vide, il n'y aura rien dans le formulaire. Avec l'id, on aura les informations correspondantes à la couleur trouvée.

        $form->handleRequest($request); // Ici on réccupère les informations du formulaire

        // Ici on protège le formulaire en vérifiant s'il est bien valide 
        // isValid signifie qu'on vérifie que toutes les contraintes mis dans le Formulaire sont bien respectées.
        if ($form->isSubmitted() && $form->isValid()) {


            $color = $form->getData(); // on réccupère les valeurs du formulaires

            $manager->persist($color); // ici, on prépare les requetes SQL
            
            $manager->flush(); // ici on exécute la requête, on effectue le INSERT ou le UPDATE selon si y'a l'id ou pas. 

            return $this->redirectToRoute('app_color'); // une fois la requete effectuée, on redirige l'utilisateur où on souhaite

        }

        return $this->render('color/index.html.twig', [
            'controller_name' => 'ColorController',
            'form' => $form->createView(), // ici on met le formulaire en page
            'colors' => $colors
        ]);
    }

    #[Route('/color/delete/{id}', name: 'app_color_delete')]
    public function delete(int $id, ColorRepository $repository, EntityManagerInterface $manager):Response
    {

        $color = $repository->find($id); // ici on réccupère la couleur qu'on désire supprimé par son ID

        $manager->remove($color); // ici on prépare la suppréssion la couleur de la base de donnée
        $manager ->flush(); // ici on supprime bien la couleur

    
    
        return $this->redirectToRoute('app_color'); // Une fois la couleur supprimée, on redirige l'utilisateur vers la page app_color 
    
    }
}
