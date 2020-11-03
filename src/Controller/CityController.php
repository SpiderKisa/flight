<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    /**
     * @Route("/city", name="city")
     */
    public function index(CityRepository $cityRepository): Response
    {
        $cities = $cityRepository->findAll();

        return $this->render('city/index.html.twig', [
            'cities' => $cities,
            'title' => "Cities",
            'createPath' => "createCity",
            'removePath' => "removeCity"

        ]);
    }

    /**
     * @Route("/createCity", name="createCity")
     */
    public function create(Request $request): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();
            return $this->redirect($this->generateUrl('city'));
        }
        return $this->render('create.html.twig', [
            'form' => $form->createView(),
            'entityName' => 'city'
        ]);
    }


    /**
     * @Route("/removeCity/{id}", name="removeCity")
     */
    public function remove(CityRepository $cityRepository, Request $request, $id): Response
    {

        $city = $cityRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($city);
        $em->flush();
        return $this->redirect($this->generateUrl('city'));
    }
}
