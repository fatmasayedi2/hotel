<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ClientType;
use App\Entity\Hotel;
use App\Entity\Client;
use App\Entity\Image;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
        public function index(): Response
        {
            return $this->render('hotel/index.html.twig', [
            'controller_name' => 'HotelController',
        ]);
    }


     /**
     * @Route("/about", name="about_page")
     */
    public function aboutPage(): Response
    {
        return $this->render('client/about.html.twig');
    }
    


     

 /**
 * @Route("/ajouter", name="add_client")
 */



public function ajouter(Request $request, EntityManagerInterface $em)
{
    $cl = new Client();
    $form = $this->createFormBuilder($cl)
        ->add('nom', TextType::class)
        ->add('nbr', TextType::class, ['label' => 'Nombre de personnes'])
        ->add('email', TextType::class, ['label' => 'Email'])
        ->add('dateArrive', DateType::class, ['label' => 'Date darrivee'])
        ->add('dateDepart', DateType::class, ['label' => 'Date de depart'])
        ->add('valider', SubmitType::class, ['label' => 'Valider'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        // Vérification si les dates sont renseignées et non null
        if ($data->getDateArrive() !== null && $data->getDateDepart() !== null) {
            // Si les dates sont valides, on peut persister l'entité
            $cl->setDateArrive($data->getDateArrive());
            $cl->setDateDepart($data->getDateDepart());

            $em->persist($cl);
            $em->flush();
            return $this->redirectToRoute('accueil');
        } else {
            // Redirection avec un message d'erreur si les dates sont manquantes
            $this->addFlash('error', 'Veuillez renseigner les dates.');
            return $this->redirectToRoute('add_client');
        }
    }

    return $this->render('client/ajouter.html.twig', [
        'f' => $form->createView(),
    ]);
}


/**
 * @Route("/", name="accueil")
 */

public function home(EntityManagerInterface $entityManager)
{
    $clientRepository = $entityManager->getRepository(Client::class);
    $clients = $clientRepository->findAll();

    return $this->render('client/home.html.twig', [
        'clients' => $clients,
    ]);
}
/**
 * @Route("/client/{id}/supprimer", name="supprimer_client")
 */
public function supprimerClient(Client $client, EntityManagerInterface $em): Response
{
    $em->remove($client);
    $em->flush();

    return $this->redirectToRoute('accueil');
}

/**
 * @Route("/client/{id}/modifier", name="modifier_client")
 */
public function modifierClient(Client $client, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(ClientType::class, $client);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    return $this->render('client/modifier.html.twig', [
        'form' => $form->createView(),
    ]);
}



}