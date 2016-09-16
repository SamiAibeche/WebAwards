<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\User;
use WebAwardsBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all Comment entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('WebAwardsBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Connecte le user après l'inscription.
     * @param User $user
     */
    private function authenticateUser(User $user)
    {
        $providerKey = 'secured_area'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }
    /**
     * Inscription de l'utilisateur
     * @Route("/register", name="user_registration")
     *
     */
    public function registerAction(Request $request)
    {

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();


        if($currentUser != "anon."){
            $this->addFlash(
                'notice',
                'Vous êtes déjà connecté ...'
            );
            return $this->redirectToRoute("homepage");
        }

        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            //Récupération des images
            $file = $user->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('users_directory'),
                $fileName
            );
            $user->setImg($fileName);
            $user->setIsPublisher(false);
            $user->setIsSubscribe(false);
            $user->setIsAdmin(false);
            $now = date("Y-m-d H:i:s");
            $user->setDateAff($now);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->authenticateUser($user);


            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'user/new.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    /*
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('WebAwardsBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
    */
    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $em = $this->getDoctrine()->getManager();


        $authorId = ($user->getId());
        $projects = $em->getRepository('WebAwardsBundle:Project')->findByIdAuthor($authorId);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'projects'=>$projects,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Finds and displays a User entity.
     *
     * @Route("/jury/{idProject}", name="user_jury_show")
     * @Method("GET")
     */
    public function showJuryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $idProject = $request->get('idProject');

        $project = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);
        $votes = $em->getRepository('WebAwardsBundle:Vote')->getVotesFrom($idProject);
        $tabIdUsers = $em->getRepository('WebAwardsBundle:Vote')->getIdUserFromVotes($votes);

        $users = $em->getRepository('WebAwardsBundle:User')->findById($tabIdUsers);
        $project = $project[0];


        return $this->render('user/jury.html.twig', array(
            'users' => $users,
            'project'=>$project,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();

        if($currentUser == "anon."){
            $this->addFlash(
                'notice',
                'Vous ne pouvez pas accéder à cette page'
            );
            return $this->redirectToRoute("homepage");
        }
        $roles = $currentUser->getRoles();

        if($roles[0] != "ROLE_ADMIN"){
            if($id !== $currentUser->getId()){
                $this->addFlash(
                    'notice',
                    'Vous ne pouvez pas accéder à cette page'
                );
                return $this->redirectToRoute("homepage");
            }
        }

        //Recup the last img
        $em = $this->getDoctrine()->getManager();
        $userData = $em->getRepository('WebAwardsBundle:User')->findById($user->getId());
        $lastImg = $userData[0]->getImg();

        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('WebAwardsBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $user->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            if(isset($lastImg) && $lastImg !== null){
                unlink("./../web/uploads/user/".$lastImg);
            }
            $file->move(
                $this->getParameter('users_directory'),
                $fileName
            );
            $user->setImg($fileName);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));

        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $userData = $em->getRepository('WebAwardsBundle:User')->findById($user->getId());
        $lastImg = $userData[0]->getImg();

        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            if(isset($lastImg) && $lastImg !== null){
                unlink("./../web/uploads/user/".$lastImg);
            }
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
