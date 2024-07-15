<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\hotel;
use App\Entity\Image;

class HotelController extends AbstractController
{
    #[Route('/hotel', name: 'app_hotel')]
    public function index(): Response
    {
        return $this->render('hotel/index.html.twig', [
            'controller_name' => 'HotelController',
        ]);
    }




    #[Route('/hotel/edit/{id}', name: 'hotel_edit')]
    public function edit($id): Response
    {
        // Logique pour éditer un hôtel spécifique (utilisation du formulaire par exemple)

        return $this->render('hotel/edit.html.twig', [
            'id' => $id,
        ]);
    }

    /**
* @Route("/", name="home")
*/
public function home(Request $request, EntityManagerInterface $em)
{
    // Creation du champ critere
    $form = $this->createFormBuilder()
        ->add("critere", TextType::class)
        ->add('Valider', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);

    $repo = $em->getRepository(Candidature::class);
    $lesCandidats = $repo->findAll();
    if ($form->isSubmitted())
    {
     $data = $form->getData();
     $lesCandidats = $repo->recherche($data['critere']);
    }

    return $this->render('hotel/home.html.twig', [
        'lesCandidats' => $lesCandidats,
        'form' => $form->createView(),
    ]);
}

 

    #[Route('/hotel/delete/{id}', name: 'hotel_delete')]
    public function delete($id): Response
    {
        // Logique pour supprimer un hôtel spécifique

        return $this->redirectToRoute('hotel_index');
    }


    /**
     * @Route("/hotel-management", name="Lien_hotel")
     */
    public function hotelManagement(): Response
    {
        // Logique pour gérer les hôtels
        // Retournez la réponse appropriée pour la gestion des hôtels

        return $this->render('hotel/gestion_hotels.html.twig');
    }

    /**
     * @Route("/client-management", name="Lien_client")
     */
    public function clientManagement(): Response
    {
        // Logique pour gérer les clients
        // Retournez la réponse appropriée pour la gestion des clients

        return $this->render('hotel/gestion_clients.html.twig');
    }

}
