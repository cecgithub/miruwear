<?php

namespace App\Controller;

use App\Entity\Size;
use App\Form\SizeType;
use App\Repository\SizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')] // protection des routes : seul les administrateurs auront accès à cette route
#[Route('/admin/size')] // on ajoute une route principale pour garantir l'accès à l'utilisateur
class SizeController extends AbstractController
{
    #[Route('/', name: 'app_size')]
    #[Route('/size/{id}', name: 'app_size_update')]
    public function index(SizeRepository $repository, EntityManagerInterface $manager, Request $request, int $id=null): Response
    {
        if ($id) {
            $size = $repository->find($id); // on cherche la couleur correspondante à l'id dans l'url
        } else {
            $size = new Size(); // ici, on appelle l'Entity afin d'insérer une couleur dans la base de donnée
        }

        $sizes = $repository->findAll();

        $form = $this->createForm(SizeType::class, $size);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $size = $form->getData();

            $manager->persist($size);

            $manager->flush();

            return $this->redirectToRoute('app_size');
        }

        return $this->render('size/index.html.twig', [
            'controller_name' => 'SizeController',
            'form' => $form->createView(),
            'sizes' => $sizes
        ]);
    }

    #[Route('/size/delete/{id}', name: 'app_size_delete')]
    public function delete(int $id, SizeRepository $repository, EntityManagerInterface $manager):Response
    {

        $size = $repository->find($id);

        $manager->remove($size);

        $manager ->flush();
  
    
        return $this->redirectToRoute('app_size'); // Une fois la couleur supprimée, on redirige l'utilisateur vers la page app_color 
    }
}
