<?php

namespace App\Controller;

use App\Entity\Type;
use App\Entity\User;
use App\Entity\GestionConge;
use App\Entity\CompteurConge;
use App\Form\CompteurCongeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GestionCongeRepository;
use App\Repository\CompteurCongeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/area/compteur/conge")
 */
class CompteurCongeController extends AbstractController
{
    
    public function __construct(EntityManagerInterface $em, GestionCongeRepository $repo)
    {
        
        foreach ($repo->findAll() as $key => $value) {
            
            if ($value->getDe() < new \DateTime('now')) {
                $em->remove($value);
                $em->flush();

            }
        }
    
    }
    /**
     * @Route("/", name="compteur_conge_index", methods={"GET"})
     */
    public function index(CompteurCongeRepository $compteurCongeRepository): Response
    {
        return $this->render('compteur_conge/index.html.twig', [
            'compteur_conges' => $compteurCongeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="compteur_conge_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $worker = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $request->request->get('worker')]);
        if (empty($worker)) {
            $worker = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $request->query->get('worker')]);
        }
        $compteurConge = new CompteurConge();
        $form = $this->createForm(CompteurCongeType::class, $compteurConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Type
            $type_id = $request->request->get('compteur_conge')['type_id'];
            $type =  $this->getDoctrine()->getRepository(Type::class)->findOneBy(['id' => $type_id]);
            
            // Compteur
            $status =  $request->request->get('status');

            // dd($status);
            
            foreach ($status as $key => $value) {
                $gestion = $this->getDoctrine()->getRepository(GestionConge::class)->findOneBy(['id' => $key]);
            }
            
            $compteurConge->setTypeId($type)
            ->addGestionconge($gestion);


            $entityManager->persist($compteurConge);

            $entityManager->flush();
            
            return $this->redirectToRoute('worker.show', ['compteur' => $compteurConge->getId() ,'worker' => $worker->getId(),'id' => $worker->getId()]);
     
        }

        return $this->render('compteur_conge/new.html.twig', [
            'compteur_conge' => $compteurConge,
            'gestion_conges' => $this->getDoctrine()->getRepository(GestionConge::class)->findAll(),
            'form' => $form->createView(),
            'worker' => $worker
        ]);
    }

    /**
     * @Route("/{id}", name="compteur_conge_show", methods={"GET"})
     */
    public function show(?CompteurConge $compteurConge): Response
    {
        if ($compteurConge == null) {
           
            return  $this->redirectToRoute('worker_index');
        }

        return $this->render('compteur_conge/show.html.twig', [
            'compteur_conge' => $compteurConge,
        ]);
        
    }

    /**
     * @Route("/{id}/edit", name="compteur_conge_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CompteurConge $compteurConge): Response
    {
        $form = $this->createForm(CompteurCongeType::class, $compteurConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compteur_conge_index');
        }

        return $this->render('compteur_conge/edit.html.twig', [
            'compteur_conge' => $compteurConge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="compteur_conge_delete", methods={"DELETE","GET","POST"})
     */
    public function delete(Request $request, CompteurConge $compteurConge): Response
    {
        $id = $compteurConge->getGestionconges()[0]->getUser()->getId();
        // if ($this->isCsrfTokenValid('delete' . $compteurConge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($compteurConge);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('worker.show',['id' => $id]);
    }
}
