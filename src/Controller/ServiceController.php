<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/area/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="service_index", methods={"GET"})
     */
    public function index(ServiceRepository $ServiceRepository): Response
    {
        return $this->render('Service/index.html.twig', [
            'services' => $ServiceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/api/onChangeSociete", name="societe_change", methods={"GET","POST"},options={"expose"=true})
     */
    public function onChangeSociete(Request $request, ServiceRepository $repo): Response
    {
        $data = [];
        $id = $request->request->get('conge')['Service'];

        $Service = $repo->findOneBy(['id' => $id]); 

        $workers = $Service->getUsers();

        
        $select = '<select id="conge_workers" name="conge[workers]" class="form-control">';
        $select .= '<option value="" disabled selected>Trouver l\'employée</option>';
        foreach ($workers as $value) {

                $select .= '<option value="'.$value->getId().'">'.$value->getName().'</option>';
        }
        $select .= '</select>';
        // dd($select);

        return new Response($select, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/new", name="service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $Service = new Service();
        $form = $this->createForm(ServiceType::class, $Service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Service);
            $entityManager->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('Service/new.html.twig', [
            'service' => $Service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service_show", methods={"GET"})
     */
    public function show(Service $Service): Response
    {
        return $this->render('Service/show.html.twig', [
            'service' => $Service,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $Service): Response
    {
        $form = $this->createForm(ServiceType::class, $Service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('Service/edit.html.twig', [
            'service' => $Service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="societe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Service $Service): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service_index');
    }
}
