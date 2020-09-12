<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\GestionConge;
use App\Entity\CompteurConge;
use App\Form\GestionCongeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GestionCongeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/area/gestion/conge")
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

        $worker = $this->getDoctrine()->getRepository(User::class)->find($request->query->get('worker'));

        $gestionConge = new GestionConge();
        $form = $this->createForm(GestionCongeType::class, $gestionConge);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            $req = $request->get('gestion_conge');

            $origin = new DateTime($req['date']);
            $target = new DateTime($req['de']);
            $interval = $origin->diff($target);
            $diff = $interval->format('%d%');

            $gestionConge->setUser($worker)
            ->setValeur($diff);


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
        $id = $gestionConge->getUser()->getId();
        // if ($this->isCsrfTokenValid('delete'.$gestionConge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gestionConge);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('compteur_conge_new',['worker' => $id ]);
    }

    // /**
    //  * @Route("/api/onClickCheckbox", name="checkbox.onchange", methods={"GET","POST"},options={"expose"=true})
    //  */
    // public function onClickCheckbox(Request $request, UserRepository $repo): Response
    // {
    //     $data = [];

    //     $id = $request->request->get('conge')['workers'];

    //     $gc = $worker->getGestionConges();

        
    //     $select = '<select id="conge_motif" name="conge[motif]" class="form-control">';
    //     $select .= '<option value="" disabled selected>Choisissez la motif du conge</option>';
    //     foreach ($gc as $cc) {

    //         foreach ($cc->getCompteurCongeId() as $compteurconge) {
    //             $value = $compteurconge->getTypeId();
    //             $select .= '<option value="'.$compteurconge->getId().'">'.$value->getName().'</option>';
    //         }
    //     }
    //     $select .= '</select>';
    //     // dd($select);

    //     return new Response($select, Response::HTTP_OK, [], true);
    // }
}
