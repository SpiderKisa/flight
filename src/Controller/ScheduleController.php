<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleType;
use App\Repository\FlightRepository;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ScheduleController extends AbstractController
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        $schedules = $scheduleRepository->findAll();

        return $this->render('schedule/index.html.twig', [
            'schedules' => $schedules,
            'title' => "Schedules",
            'createPath' => "createSchedule",
            'removePath' => "removeSchedule"

        ]);
    }

    /**
     * @Route("/createSchedule", name="createSchedule")
     */
    public function create(Request $request, FlightRepository $flightRepository): Response
    {
        $schedule = new Schedule();
        $form = $this->createForm(ScheduleType::class, $schedule);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $schedule = $form->getData();
            $wayIsOk = false;
            $flightId = $form->getData()->getFlight()->getId();
            $possibleWays = $flightRepository->findOneById($flightId)->getWay();

            $selectedWay = $form->getData()->getWay();

            foreach ($possibleWays as $way) {
                if ($selectedWay === $way) {
                    $wayIsOk = true;
                }
            }

            if (!$wayIsOk) {
                return $this->redirect($this->generateUrl('createSchedule'));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($schedule);
            $em->flush();
            return $this->redirect($this->generateUrl('schedule'));
        }
        return $this->render('create.html.twig', [
            'form' => $form->createView(),
            'entityName' => 'schedule'
        ]);
    }

    /**
     * @Route("/removeSchedule/{id}", name="removeSchedule")
     */
    public function remove(ScheduleRepository $scheduleRepository, Request $request, $id): Response
    {

        $schedule = $scheduleRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($schedule);
        $em->flush();
        return $this->redirect($this->generateUrl('schedule'));
    }
}
