<?php

namespace App\Controller;

use App\Entity\CompteurConge;
use App\Entity\GestionConge;
use App\Entity\Worker;
use App\Form\GestionCongeType;
use App\Repository\GestionCongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gestion/conge")
 */
class GestionCongeController extends AbstractController
{
    /**
     * @Route("/", name="gestion_conge_index", methods={"GET"})
     */
    public function index(GestionCongeRepository $gestionCongeRepository): Response
    {
        return $this->render('gestion_conge/index.html.twig', [
            'gestion_conges' => $gestionCongeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gestion_conge_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $worker = $this->getDoctrine()->getRepository(Worker::class)->findOneBy(['id' => $request->query->get('worker')]);
        $gestionConge = new GestionConge();
        $form = $this->createForm(GestionCongeType::class, $gestionConge);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $gestionConge->setWorkerId($worker);

            $entityManager->persist($gestionConge);

            $entityManager->flush();

            // dd($worker->getId());

            return $this->redirectToRoute('compteur_conge_new',['gestion' => $gestionConge->getId(),'worker' => $worker->getId(),'id' => $worker->getId()]);
            // dd($request);
        }

        return $this->render('gestion_conge/new.html.twig', [
            'gestion_conge' => $gestionConge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gestion_conge_show", methods={"GET"})
     */
    public function show(?GestionConge $gestionConge): Response
    {
        if ($gestionConge == null) {
           
            return  $this->redirectToRoute('worker_index');
        }
        
        return $this->render('gestion_conge/show.html.twig', [
            'gestion_conge' => $gestionConge,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gestion_conge_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GestionConge $gestionConge): Response
    {
        $form = $this->createForm(GestionCongeType::class, $gestionConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gestion_conge_index');
        }

        return $this->render('gestion_conge/edit.html.twig', [
            'gestion_conge' => $gestionConge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="gestion_conge_delete", methods={"DELETE","GET","POST"})
     */
    public function delete(Request $request, GestionConge $gestionConge): Response
    {
        $id = $gestionConge->getWorkerId()->getId();
        // if ($this->isCsrfTokenValid('delete'.$gestionConge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gestionConge);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('compteur_conge_new',['worker' => $id ]);
    }
}
