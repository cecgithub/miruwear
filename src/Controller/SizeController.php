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
    #[Route('/list', name: 'app_size_list')]
    #[Route('/list/update/{id}', name: 'app_size_update')]
    public function sizes(Size $size = null, Request $request, SizeRepository $sizeRepository, EntityManagerInterface $manager): Response
    {
        if ($size === null) {
            $size = new Size();
        }

        $form = $this->createForm(SizeType::class, $size);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $size = $form->getData();
            $manager->persist($size);

            $manager->flush();

            return $this->redirectToRoute('app_size_list');
        }

        return $this->render('size/index.html.twig', [
            'sizes' => $sizeRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_size_delete')]
    public function delete(int $id, SizeRepository $repository, EntityManagerInterface $manager): Response
    {
        $size = $repository->find($id);
        $manager->remove($size);
        $manager->flush();

        return $this->redirectToRoute('app_size_list'); // Une fois la couleur supprimée, on redirige l'utilisateur vers la page app_color 
    }
}
