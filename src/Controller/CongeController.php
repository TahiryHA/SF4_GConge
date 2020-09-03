<?php

namespace App\Controller;

use App\Entity\CompteurConge;
use App\Entity\Type;
use App\Entity\Conge;
use App\Form\CongeType;
use App\Repository\CompteurCongeRepository;
use App\Repository\CongeRepository;
use App\Services\ApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/conge")
 */
class CongeController extends AbstractController
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @Route("/", name="conge_index", methods={"GET"})
     */
    public function index(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/api/onChangeMotif", name="motif_change", methods={"GET","POST"},options={"expose"=true})
     */
    public function onChangeMotif(Request $request, CompteurCongeRepository $repo): Response
    {
        $data = [];
        $id = $request->request->get('conge')['motif'];

        $compteur = $repo->findOneBy(['id' => $id]); 


        // dump($id,$compteur);

        
        $input = '<input type="number" id="conge_qte_dispo" name="conge[qte_dispo]" value="'.$compteur->getRestant().'" required="required" class="form-control">';
        // dd($select);

        return new Response($input, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/valide", name="conge_valide", methods={"GET"})
     */
    public function valide(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/valide.html.twig', [
            'conges' => $congeRepository->findBy(['status' => true]),
        ]);
    }

    /**
     * @Route("/new", name="conge_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $conge = new Conge();
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // dd($request);

            // dump($form->getData());
            $entityManager = $this->getDoctrine()->getManager();
            
            $conge->addWorker($form->get('workers')->getData());
            $conge->setStatus(false);
            $conge->setDateVerif(new \DateTime());
            // $conge->setQteDispo($request->request->get('conge')['qte_dispo']);

            $cc = $this->getDoctrine()->getRepository(CompteurConge::class)->findOneBy(['id' => $request->request->get('conge')['motif']]);
            $conge->setMotif($cc->getTypeId()->getName());

            //Increment / decrement
            $compteur = $this->getDoctrine()->getRepository(CompteurConge::class)->findOneBy(['id' => $cc]);
     
            $new_value = $compteur->getRestant() - $request->request->get('conge')['duree'];
            $compteur->setRestant($new_value);

            $entityManager->persist($compteur);

            
            $entityManager->persist($conge);
            $entityManager->flush();

            return $this->redirectToRoute('conge_index');
        }

        return $this->render('conge/new.html.twig', [
            'conge' => $conge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="conge_show", methods={"GET"})
     */
    public function show(Conge $conge): Response
    {
        return $this->render('conge/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    /**
     * Change status
     * @Route("/conge/{id}/status", name="conge_status", options={"expose" = true})
     */
    public function status(Conge $conge)
    {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('index');
        }
        
        if ($conge->getStatus()) {

            $icon = "warning";
            $title = 'Status is now disable';

        }
        else{

            $icon = "success";
            $title = 'Status is now enable';
        }

        $conge->addUser($this->getUser());
        $conge->setDateVerif(new \DateTime());

        $this->apiService->status($conge);

        return $this->redirectToRoute('conge_index');

        // return new JsonResponse(array(
        //     'icon' => $icon,
        //     'title' => $title
        // ), 200);
    }

    /**
     * @Route("/{id}/edit", name="conge_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Conge $conge): Response
    {
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        // $form->remove('motif')
        // ->remove('societe')
        // ->remove('workers')
        // ->remove('date_demande')
        // ;

        if ($form->isSubmitted()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('conge_index');
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="conge_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Conge $conge): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conge_index');
    }
}
