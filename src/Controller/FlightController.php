<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Form\FlightType;
use App\Repository\FlightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\DBAL\DriverManager;


class FlightController extends AbstractController
{
    /**
     * @Route("/flight", name="flight")
     */
    public function index(FlightRepository $flightRepository): Response
    {
        $flights = $flightRepository->findAll();

        return $this->render('flight/index.html.twig', [
            'flights' => $flights,
            'title' => "Flights",
            'createPath' => "createFlight",
            'removePath' => "removeFlight"

        ]);
    }

    /**
     * @Route("/createFlight", name="createFlight")
     */
    public function create(Request $request): Response
    {
        $flight = new Flight();
        $form = $this->createForm(FlightType::class, $flight);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $flight = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();
            return $this->redirect($this->generateUrl('flight'));
        }
        return $this->render('create.html.twig', [
            'form' => $form->createView(),
            'entityName' => 'flight'
        ]);
    }


    /**
     * @Route("/removeFlight/{id}", name="removeFlight")
     */
    public function remove(FlightRepository $flightRepository, Request $request, $id): Response
    {

        $flight = $flightRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($flight);
        $em->flush();
        return $this->redirect($this->generateUrl('flight'));
    }
}
