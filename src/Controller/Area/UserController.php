<?php

namespace App\Controller\Area;


use App\Entity\User;
use App\Utils\Utils;
use App\Form\UserType;
use App\Entity\Parameter;
use App\Services\ApiService;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\Connection;
use App\Notification\UserNotification;
use App\Repository\GestionCongeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    protected $apiService;
    protected $utils;
    protected $em;
    protected $breadcrumbs;
    protected $db;

    public function __construct(ApiService $apiService, Utils $utils, Connection $db)
    {
        $this->apiService = $apiService;
        $this->db = $db;
        $this->utils = $utils;
        $this->em = $this->utils->_em();
        $this->breadcrumbs = $utils->_breadcrumbs();
    }

     /**
     * @Route("/api/onChangeWorkers", name="workers_change", methods={"GET","POST"},options={"expose"=true})
     */
    public function onChangeWorkers(Request $request, UserRepository $repo): Response
    {
        $data = [];
        // $id = $request->request->get('conge')['workers'];

        $worker = $repo->findOneBy(['id' => $this->getUser()->getId()]); 

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
     *@Route("area/user", name="user.index",options={"expose"=true})
     */
    public function index()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {

            $this->breadcrumbs->prependRouteItem("Acceuil", "index");
            $this->breadcrumbs->addItem("Utilisateur");
            $this->breadcrumbs->addItem("Liste");

            return $this->render("area/user/_index.html.twig", [
                'active' => "Utilisateur",
                'page_parent' => "Utilisateur",
                'page_child' => "Liste",
                'page_action' => null,
                'users' => $this->utils->_repository(User::class)->findAll(),

                "user" => $this->getUser(),
            ]);
        }
    }

    /**
     * @Route("user/list", name="user.list", options={"expose"=true})
     */
    public function list(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute('app.login');

        } 
        else{

            if (!$request->isXmlHttpRequest()) {

                return new JsonResponse(array('message' => 'Use only ajax!'), 400);

            }

            $users = $this->utils->_repository(User::class)->findAll();
            $output = array(
                'data' => array()
            );

            foreach ($users as $user) {

                $roles = [
                    "ROLE_SUPER_ADMIN" => 'danger',
                    "ROLE_ADMIN" => 'secondary',
                    "ROLE_EMPLOYER" => 'success',
                    "ROLE_WORKER" => 'primary',
                    "ROLE_USER" => 'info'
                ];

                $role = [];

                foreach ($user->getRoles() as $v1) {
                    foreach ($roles as $j => $v2) {
                        if ($v1 == $j) {
                            $role[] = '<span class="badge badge-' . $v2 . '">' . $j . '</span>';
                        }
                    }
                }

                $btn = '<div class="btn-group">';
                $btn .= '<a  class="btnEditUser" id="' . $user->getId() . '" href="" data-toggle="tooltip" data-placement="top" title="Modifier le profil">';
                $btn .= '<i class="fas fa-edit "></i>';
                $btn .= '</a>';
                $btn .= '&nbsp;&nbsp;';

                if (!in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
                    if ($user !== $this->getUser()) {
                        $btn .= '<a class="btnDeleteUser" id="' . $user->getId() . '" data-placement="top" data-target="#confirmDeleteUserModal" data-username="{{ user.USERNAME }}" data-ad-type="user" data-ad-id="{{ user.ID }}" data-ad-name="{{ user.USERNAME }}" data-user-id="' . $user->getId() . '" data-toggle="modal" style="cursor:pointer;" title="Supprimer ' . $user->getUsername() . '">';
                        $btn .= '<i class="fas fa-trash-alt text-danger"></i>';
                        $btn .= '</a>';
                        $btn .= '</div>';
                    }
                }

                $output['data'][] = [
                    'matricule' => $user->getMATRICULE(),
                    'username' => "<a href=''>" . $user->getUsername() . "</a>",
                    'email' => $user->getEmail(),
                    'role' => $role,
                    'action' => $btn
                ];
            }

            return $this->utils->_json($output);
        }
    }

    /**
     *@Route("user/add", name="user.add", options={"expose"=true})
     */
    public function add(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserNotification $notification
    ) {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {
            if (!$request->isXmlHttpRequest()) {
                return new JsonResponse(array('message' => 'Use only ajax!'), 400);
            }

            $user = new User();
            $users = $this->utils->_repository(User::class)->findAll();


            $data = array();

            foreach ($users as $value) {
                $data[] = [
                    $value->getUsername(),
                    $value->getEmail()
                ];
            }

            $userForm = $this->createForm(UserType::class, $user);
            $userForm->handleRequest($request);

            if ($request->isMethod("POST")) {

                foreach ($data as $value) {
                    foreach ($value as $val) {
                        if ($val == $userForm['username']->getData() || $val == $userForm['email']->getData()) {
                            return new JsonResponse(array("message" => $val . " est déjà utilisé!"), 500);
                            break;
                        }
                    }
                }

                $roles[] = $request->request->get('user')['role'];
                $user->setRoles($roles);
                $user->setMatricule(strtoupper(substr(md5(uniqid(rand(1, 6))), 0, 6)));
                $user->setImage("https://cdn4.iconfinder.com/data/icons/e-commerce-icon-set/48/Username_2-512.png");

                // $password = $this->randomPassword();
                $password = 123456;
                $user->setPassword($passwordEncoder->encodePassword($user, $password));

                $parameter = $this->utils->_repository(Parameter::class)->findOneBy(["CODE" => "PARAMETER.EMAIL"]);
                $email = $parameter->getValue();

                $notification->notify($user, $password, $email);

                $this->utils->_persist($user);

                return new JsonResponse(array('message' => 'Success!'), 200);
            }

            return $this->render("area/user/_add.html.twig", [
                'userForm' => $userForm->createView(),
            ]);
        }
    }

    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    /**
     * @Route("user/edit/{id}", name="user.edit", options={"expose"=true})
     */
    public function edit(User $user, Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {
            if (!$request->isXmlHttpRequest()) {
                return new JsonResponse(array('message' => 'Use only ajax!'), 400);
            }

            $users = $this->utils->_repository(User::class)->findAll();

            $data = array();

            foreach ($users as $value) {
                if ($value != $user) {
                    $data[] = [
                        $value->getUsername(),
                        $value->getEmail()
                    ];
                }
            }

            $userForm = $this->createForm(UserType::class, $user);
            $userForm->handleRequest($request);

            if ($request->isMethod("POST")) {

                foreach ($data as $value) {
                    foreach ($value as $val) {
                        if ($val == $userForm['username']->getData() || $val == $userForm['email']->getData()) {
                            return new JsonResponse(array("message" => $val . " est déjà utilisé!"), 500);
                            break;
                        }
                    }
                }

                if ($user === $this->getUser()) {
                } else {
                    $roles[] = $_POST['role'];
                    $user->setRoles($roles);
                }

                $this->utils->_persist($user);

                return new JsonResponse(array($user->getUsername()), 200);
            }

            return $this->render("area/user/_edit.html.twig", [
                'userForm' => $userForm->createView(),
                'user' => $user,
                'active' => $this->getUser()
            ]);
        }
    }

    /**
     * @Route("user/edit_profile/{id}", name="user.edit_profile", options={"expose"=true})
     */
    public function editProfile(User $user, Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {
            if (!$request->isXmlHttpRequest()) {
                return new JsonResponse(array('message' => 'Use only ajax!'), 400);
            }

            $userForm = $this->createForm(UserProfileType::class, $user);
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {

                if ($user === $this->getUser()) {
                } else {

                    $roles[] = $_POST['role'];
                    $user->setRoles($roles);
                }

                $this->utils->_persist($user);
                return new JsonResponse(array($user->getUsername()), 200);
            }

            return $this->render("area/user/_editProfile.html.twig", [
                'userForm' => $userForm->createView(),
                'user' => $user,
                'active' => $this->getUser()
            ]);
        }
    }

    /**
     * @Route("user/change_password/{id}", name="user.change_password", options={"expose"=true})
     */
    public function changePassword(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {
            if (!$request->isXmlHttpRequest()) {
                return new JsonResponse(array('message' => 'Use only ajax!'), 400);
            }

            if ($user != $this->getUser()) {
            } else {
                if ($request->isMethod('post')) {

                    $password1 = $request->get("password1");
                    $password2 = $request->get("password2");
                    $password = $request->get("password");

                    $pass =  $passwordEncoder->isPasswordValid($user, $password);

                    if (!$pass) {
                        return new Response(json_encode(array("message" => "Mot de passe actuel incorrect!")), 500, ['Content-Type' => 'application/json']);
                    } else {
                        if ($password1 === $password2) {
                            $entityManager = $this->getDoctrine()->getManager();
                            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password1')));

                            $entityManager->persist($user);
                            $entityManager->flush();

                            return new JsonResponse(array($user->getUsername()), 200);
                        } else {
                            return new Response(json_encode(array("message" => "Les mots de passes ne se correspondent pas!")), 500, ['Content-Type' => 'application/json']);
                        }
                    }
                }

                return $this->render("area/user/_changePassword.html.twig", [
                    'user' => $user,
                    'active' => $this->getUser()
                ]);
            }
        }
    }


    /**
     * @Route("user/delete/{id}", name="user.delete", options={"expose" = true})
     */
    public function delete(User $user, Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {
            if (!$request->isXmlHttpRequest()) {
                return new JsonResponse(array('message' => 'Use only ajax!'), 400);
            }

            $this->utils->_remove($user);

            return new Response();
        }
    }

    /**
     *@Route("area/worker", name="worker.index",options={"expose"=true})
     */
    public function getAllWorker(UserRepository $repo)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {

            $this->breadcrumbs->prependRouteItem("Acceuil", "index");
            $this->breadcrumbs->addItem("Employée");
            $this->breadcrumbs->addItem("Liste");

            $workers = $repo->findAll();
            $role = ['ROLE_EMPLOYER'];
            $data = [];
            foreach ($workers as $key => $value) {
                if ( $value->getRoles() == $role ) {
                    $data[] = [
                        'id' => $value->getId(),
                        'username' => $value->getUsername(),
                        'service' => $value->getService()->getName(),
                        'matricule' => $value->getMATRICULE()

                    ];
                }
            }

            return $this->render("worker/index.html.twig", [
                
                'workers' => $data,

                "user" => $this->getUser(),
            ]);
        }
    }

    /**
     *@Route("area/worker/{id}", name="worker.show",options={"expose"=true})
     */
    public function getWorker(User $user)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {

            $this->breadcrumbs->prependRouteItem("Acceuil", "index");
            $this->breadcrumbs->addItem("Employée");
            $this->breadcrumbs->addItem("Liste");

            $gc = $user->getGestionConges();

            $data = [];

            foreach ($gc as $key => $value) {
                foreach ($value->getCompteurCongeId() as $key => $val) {
                    $data [] = $val;
                }
            }
            // dd($data);

            return $this->render("worker/show.html.twig", [
                
               'worker' => $user,
               'compteur_conges' => $data
            ]);
        }
    }

    /**
     *@Route("area/worker/add", name="worker.add", options={"expose"=true})
     */
    public function addWorker(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserNotification $notification
    ) {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app.login');
        } else {
            if (!$request->isXmlHttpRequest()) {
                return new JsonResponse(array('message' => 'Use only ajax!'), 400);
            }

            $user = new User();
            $users = $this->utils->_repository(User::class)->findAll();


            $data = array();

            foreach ($users as $value) {
                $data[] = [
                    $value->getUsername(),
                    $value->getEmail()
                ];
            }

            $userForm = $this->createForm(UserType::class, $user);
            $userForm->handleRequest($request);

            if ($request->isMethod("POST")) {

                foreach ($data as $value) {
                    foreach ($value as $val) {
                        if ($val == $userForm['username']->getData() || $val == $userForm['email']->getData()) {
                            return new JsonResponse(array("message" => $val . " est déjà utilisé!"), 500);
                            break;
                        }
                    }
                }

                $roles[] = $_POST['role'];
                $user->setRoles($roles);
                $user->setMatricule(strtoupper(substr(md5(uniqid(rand(1, 6))), 0, 6)));
                $user->setImage("https://cdn4.iconfinder.com/data/icons/e-commerce-icon-set/48/Username_2-512.png");

                // $password = $this->randomPassword();
                $password = 123456;
                $user->setPassword($passwordEncoder->encodePassword($user, $password));

                $parameter = $this->utils->_repository(Parameter::class)->findOneBy(["CODE" => "PARAMETER.EMAIL"]);
                $email = $parameter->getValue();

                $notification->notify($user, $password, $email);

                $this->utils->_persist($user);

                return new JsonResponse(array('message' => 'Success!'), 200);
            }

            return $this->render("worker/new.html.twig", [
                'userForm' => $userForm->createView(),
            ]);
        }
    }
}
