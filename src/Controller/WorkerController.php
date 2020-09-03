<?php

namespace App\Controller;

use App\Entity\CompteurConge;
use App\Entity\Worker;
use App\Form\WorkerType;
use App\Repository\WorkerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/worker")
 */
class WorkerController extends AbstractController
{
    /**
     * @Route("/", name="worker_index", methods={"GET"})
     */
    public function index(WorkerRepository $workerRepository): Response
    {
        return $this->render('worker/index.html.twig', [
            'workers' => $workerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="worker_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $worker = new Worker();
        $form = $this->createForm(WorkerType::class, $worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($worker);
            $entityManager->flush();

            return $this->redirectToRoute('worker_index');
        }

        return $this->render('worker/new.html.twig', [
            'worker' => $worker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="worker_show", methods={"GET"})
     */
    public function show(Worker $worker): Response
    {
        $gc = $worker->getGestionConges();
        $data = [];
        foreach ($gc as $key => $value) { 
            $data[] = $value->getCompteurCongeId();
        }
        // dd($data);
        return $this->render('worker/show.html.twig', [
            'worker' => $worker,
            'compteur_conges' => $data
        ]);
    }

    /**
     * @Route("/api/onChangeWorkers", name="workers_change", methods={"GET","POST"},options={"expose"=true})
     */
    public function onChangeWorkers(Request $request, WorkerRepository $repo): Response
    {
        $data = [];
        $id = $request->request->get('conge')['workers'];

        $worker = $repo->findOneBy(['id' => $id]); 

        $gc = $worker->getGestionConges();

        
        $select = '<select id="conge_motif" name="conge[motif]" class="form-control">';
        $select .= '<option value="" disabled selected>Choisissez la motif du conge</option>';
        foreach ($gc as $cc) {

            foreach ($cc->getCompteurCongeId() as $compteurconge) {
                $value = $compteurconge->getTypeId();
                $select .= '<option value="'.$compteurconge->getId().'">'.$value->getName().'</option>';
            }
        }
        $select .= '</select>';
        // dd($select);

        return new Response($select, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/{id}/edit", name="worker_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Worker $worker): Response
    {
        $form = $this->createForm(WorkerType::class, $worker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('worker_index');
        }

        return $this->render('worker/edit.html.twig', [
            'worker' => $worker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="worker_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Worker $worker): Response
    {
        if ($this->isCsrfTokenValid('delete'.$worker->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($worker);
            $entityManager->flush();
        }

        return $this->redirectToRoute('worker_index');
    }
}
