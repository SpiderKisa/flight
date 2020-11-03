<?php

namespace App\Controller;

use App\Form\SearchInputType;
use App\Repository\AirportRepository;
use App\Repository\ScheduleRepository;
use App\Repository\WayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchingController extends AbstractController
{
    private $departureCity;
    private $destinationCity;
    private $departureAirports;
    private $destinationAirports;
    private $ways;
    private $schedules;

    /**
     * @Route("/searching", name="searching")
     */
    public function index(Request $request,
                          AirportRepository $airportRepository,
                          WayRepository $wayRepository,
                          ScheduleRepository $scheduleRepository): Response
    {
        $form = $this->createForm(SearchInputType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->departureCity = $form->getData()['departureCity'];
            $this->destinationCity = $form->getData()['destinationCity'];
            $this->departureAirports = $airportRepository->findByCity($this->departureCity);
            $this->destinationAirports = $airportRepository->findByCity($this->destinationCity);
            $this->ways = $wayRepository->findByDepAndDestAirports($this->departureAirports, $this->destinationAirports);
            $this->schedules = $scheduleRepository->findByWays($this->ways);

            return $this->render('searchingResult.html.twig', [
                'schedules' => $this->schedules,
                'departureCity' => $this->departureCity,
                'destinationCity' => $this->destinationCity,
            ]);
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Choosing cities',
        ]);
    }

}
