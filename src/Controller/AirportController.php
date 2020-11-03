<?php

namespace App\Controller;

use App\Entity\Airport;
use App\Form\AirportType;
use App\Repository\AirportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AirportController extends AbstractController
{
    /**
     * @Route("/airport", name="airport")
     */
    public function index(AirportRepository $airportRepository): Response
    {
        $airports = $airportRepository->findAll();

        return $this->render('airport/index.html.twig', [
            'airports' => $airports,
            'title' => "Airports",
            'createPath' => "createAirport",
            'removePath' => "removeAirport"

        ]);
    }

    /**
     * @Route("/createAirport", name="createAirport")
     */
    public function create(Request $request): Response
    {
        $airport = new Airport();
        $form = $this->createForm(AirportType::class, $airport);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $airport = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($airport);
            $em->flush();
            return $this->redirect($this->generateUrl('airport'));
        }
        return $this->render('create.html.twig', [
            'form' => $form->createView(),
            'entityName' => 'airport'
        ]);
    }


    /**
     * @Route("/removeAirport/{id}", name="removeAirport")
     */
    public function remove(AirportRepository $airportRepository, Request $request, $id): Response
    {

        $airport = $airportRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($airport);
        $em->flush();
        return $this->redirect($this->generateUrl('airport'));
    }
}
