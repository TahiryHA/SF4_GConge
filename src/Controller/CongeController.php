<?php

namespace App\Controller;

use DateTime;
use App\Entity\Type;
use App\Entity\Conge;
use App\Form\CongeType;
use App\Services\ApiService;
use App\Entity\CompteurConge;
use App\Repository\CongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CompteurCongeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/area/conge")
 */
class CongeController extends AbstractController
{
    protected $apiService;

    public function __construct(ApiService $apiService,EntityManagerInterface $em, CompteurCongeRepository $repo)
    {
        $this->apiService = $apiService;
        
        foreach ($repo->findAll() as $key => $value) {
            
            if ($value->getRestant() <= 0) {
                $em->remove($value);
                $em->flush();

            }
        }
    
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

            $req = $request->request->get('conge');

            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $origin = new DateTime($req['date_deb']);
            $target = new DateTime($req['date_fin']);
            $interval = $origin->diff($target);
            $diff = $interval->format('%d%');
            
            // dd($diff);
            // $conge->addWorker($form->get('workers')->getData());
            $conge->addUser($this->getUser());
            $conge->setStatus(false);
            $conge->setDateVerif(new \DateTime());
            $conge->setDuree($diff);


            // $conge->setQteDispo($request->request->get('conge')['qte_dispo']);

            $cc = $this->getDoctrine()->getRepository(CompteurConge::class)->findOneBy(['id' => $request->request->get('conge')['motif']]);
            $conge->setMotif($cc->getTypeId()->getName());

            //Increment / decrement
            $compteur = $this->getDoctrine()->getRepository(CompteurConge::class)->findOneBy(['id' => $cc]);
     
            $new_value = $compteur->getRestant() - $conge->getDuree();
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
        $conge->addUserValid($this->getUser());
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
        // ->remove('Service')
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
