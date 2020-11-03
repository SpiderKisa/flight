<?php

namespace App\Controller;

use App\Entity\Way;
use App\Form\WayType;
use App\Repository\WayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WayController extends AbstractController
{
    /**
     * @Route("/way", name="way")
     */
    public function index(WayRepository $wayRepository): Response
    {
        $ways = $wayRepository->findAll();

        return $this->render('way/index.html.twig', [
            'ways' => $ways,
            'title' => "Ways",
            'createPath' => "createWay",
            'removePath' => "removeWay"

        ]);
    }

    /**
     * @Route("/createWay", name="createWay")
     */
    public function create(Request $request): Response
    {
        $way = new Way();
        $form = $this->createForm(WayType::class, $way);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $way = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($way);
            $em->flush();
            return $this->redirect($this->generateUrl('way'));
        }
        return $this->render('create.html.twig', [
            'form' => $form->createView(),
            'entityName' => 'way'
        ]);
    }


    /**
     * @Route("/removeWay/{id}", name="removeWay")
     */
    public function remove(WayRepository $wayRepository, Request $request, $id): Response
    {

        $way = $wayRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($way);
        $em->flush();
        return $this->redirect($this->generateUrl('way'));
    }
}
