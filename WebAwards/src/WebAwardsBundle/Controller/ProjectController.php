<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\Project;
use WebAwardsBundle\Entity\Winner;
use WebAwardsBundle\Entity\User;
use WebAwardsBundle\Entity\Vote;
use WebAwardsBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Response;


/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project_index")
     * @Method("GET")
     */
    public function indexAction($page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        
        $projects = $em->getRepository('WebAwardsBundle:Project')->getAllPosts($page);
        $totalPostsReturned = $projects->getIterator()->count();
        $totalProjects = $projects->count();
        $maxPage = ceil($totalProjects/6);
        $iterator = $projects->getIterator();




        //Get the Winner of the day
        $winner = $em->getRepository('WebAwardsBundle:Winner')->findBy(
            array('isDay' => '1')
        );

        foreach($winner as $win){
            $idProject = $win->getIdProject();
        }
        $winner = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);

        //Get the author of the project
        $idUser = $winner[0]->getIdAuthor();
        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);

        //Get the vote of the project
        $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($idProject);

        //Get the number of like
        $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);

        //Verify if the current user has like
        $hasLike = "";
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($currentUser != "anon.") { //If the user is connected
            $roles = $currentUser->getRoles();
            if ($roles[0] != "ROLE_ADMIN") { //if isn't an Admin
                $hasLike = $em->getRepository('WebAwardsBundle:Heart')->verifyHasLike($idProject, $currentUser);
            }
        }

        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'winner'   => $winner,
            'nbHeart'  => $nbHeart,
            'hasLike'   => $hasLike,
            'user'     => $user,
            'vote'     => $vote,
            'maxPages' => $maxPage,
            'thisPage' => $page,
        ));

    }
    /**
     * Creates a new Project entity.
     *
     * @Route("/new", name="project_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm('WebAwardsBundle\Form\ProjectType', $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération des images
            $fileScreen = $project->getImgScreen();
            $fileMobile = $project->getImgMobile();

            //Parse du nom de l'image -> Nom unique
            $fileName = md5(uniqid()).'.'.$fileScreen->guessExtension();
            $fileNameMobile = md5(uniqid()).'.'.$fileMobile->guessExtension();

            //Ajout au dossier Project
            $fileScreen->move(
                $this->getParameter('projects_directory'),
                $fileName
            );
            $fileMobile->move(
                $this->getParameter('projects_directory'),
                $fileNameMobile
            );

            //Paramètre les images
            $project->setImgScreen($fileName);
            $project->setImgMobile($fileNameMobile);

            //Paramètre les variables
            //IdAuthor
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $idUser = $user->getId();
            $project->setIdAuthor($user);
            //Initialisation des données par défaut
            $project->setNbLike(0);
            $project->setIsForward(false);
            $project->setIsVisible(false);
            $now = date("Y-m-d H:i:s");
            $project->setDateAdd($now);

            //Envoi des données
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return $this->render('project/new.html.twig', array(
            'project' => $project,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method("GET")
     */
    public function showAction(Project $project)
    {
        //Vérifie si le projet est "Visible" (Si l'admin l'a validé)
        if(!($project->getIsVisible())){
            return $this->redirectToRoute("homepage");
        }

        //Initialisation des variables
        $hasLike = 0;
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($project);
        $form = $this->createForm('WebAwardsBundle\Form\VoteType');
        $formComment = $this->createForm('WebAwardsBundle\Form\CommentType');
        $user = $project->getIdAuthor();
        $idUser = $user->getId();
        $idProject = $project->getId();


        //Récupération de l'auteur du projet
        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);

        //Récupération des votes du projet
        $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($idProject);
        $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);

        //Récupération des commentaires liés au projet
        $comments = $em->getRepository('WebAwardsBundle:Comment')->findByIdProject($idProject);

        //Vérifie que l'utilisateur ait déjà like le projet
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($currentUser != "anon.") {
            $roles = $currentUser->getRoles();
            if ($roles[0] != "ROLE_ADMIN") {
                $hasLike = $em->getRepository('WebAwardsBundle:Heart')->verifyHasLike($idProject, $currentUser);
            }
        }

        return $this->render('project/show.html.twig', array(
            'project'   => $project,
            'user'      => $user,
            'nbHeart'   => $nbHeart,
            'vote'      => $vote,
            'hasLike'   => $hasLike,
            'comments'  => $comments,
            'delete_form' => $deleteForm->createView(),
            'form'      => $form->createView(),
            'formComment' => $formComment->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Project $project)
    {
        //Récupération des donées de l'utilisateur en cours
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        //Si il n'est pas connecté
        if($currentUser == "anon."){
            $this->addFlash(
                'notice',
                'Vous ne pouvez pas accéder à cette page'
            );
                return $this->redirectToRoute("homepage");
        }
        //Si ce n'est pas un admin
        $roles = $currentUser->getRoles();
        if($roles[0] != "ROLE_ADMIN" ){
                $this->addFlash(
                    'notice',
                    'Vous ne pouvez pas accéder à cette page'
                );
                return $this->redirectToRoute("homepage");
        }

        //Initialisation des variables
        $id = $project->getId();

        //Img
        $em = $this->getDoctrine()->getManager();
        $projectData = $em->getRepository('WebAwardsBundle:Project')->findById($id);

        $lastImgScreen = $projectData[0]->getImgScreen();
        $lastImgMobile = $projectData[0]->getImgMobile();

        //Form
        $editForm = $this->createForm('WebAwardsBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        $deleteForm = $this->createDeleteForm($project);

        //Vérifie si le formulaire a été envoyé && qu'il est valide
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            //Récupération des images postées
            $fileScreen = $project->getImgScreen();
            $fileMobile = $project->getImgMobile();
            //Nom unique pour chaque image
            $fileScreenName = md5(uniqid()).'.'.$fileScreen->guessExtension();
            $fileMobileName = md5(uniqid()).'.'.$fileMobile->guessExtension();


            //Vérifie que l'utilisateur avec bien des images au préalable et les supprime
            if(isset($lastImgScreen) && $lastImgScreen !== null && isset($lastImgMobile) && $lastImgMobile !== null ){
                unlink("./../web/uploads/project/".$lastImgScreen);
                unlink("./../web/uploads/project/".$lastImgMobile);
            }

            //Place les nouvelles images dans le dossier uploads/Projet
            $fileScreen->move(
                $this->getParameter('projects_directory'),
                $fileScreenName
            );
            $fileMobile->move(
                $this->getParameter('projects_directory'),
                $fileMobileName
            );

            //Set les nouvelles images
            $project->setImgScreen($fileScreenName);
            $project->setImgMobile($fileMobileName);

            //Envoi des données dans la DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            //Success message
            $this->addFlash(
                'notice',
                'Modification enregistrée'
            );

            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        return $this->render('project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}", name="project_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $screen = $project->getImgScreen();
            $mobile = $project->getImgMobile();

            //Vérification de l'existance des images et suppression
            if(isset($screen) && isset($mobile)){
                unlink("./../web/uploads/project/".$mobile);
                unlink("./../web/uploads/project/".$screen);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * Creates a form to delete a Project entity.
     *
     * @param Project $project The Project entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Displays the number of project present in the page and set the navigation
     *
     * @Route("/page/{page}", name="project_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $page = $request->get('page');

        $projects = $em->getRepository('WebAwardsBundle:Project')->getAllPosts($page);
        $totalPostsReturned = $projects->getIterator()->count();
        $totalProjects = $projects->count();
        $iterator = $projects->getIterator();
        $maxPage = ceil($totalProjects/6);

        //Get the Winner of the day
        $winner = $em->getRepository('WebAwardsBundle:Winner')->findBy(
            array('isDay' => '1')
        );

        foreach($winner as $win){
            $idProject = $win->getIdProject();
        }
        $winner = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);

        //Get the author of the project
        $idUser = $winner[0]->getIdAuthor();
        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);

        //Get the vote of the project
        $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($idProject);

        $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);
        //Get the last project of the Month

        //All Winner of the month
        $hasLike = "";
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($currentUser != "anon.") {
            $roles = $currentUser->getRoles();
            if ($roles[0] != "ROLE_ADMIN") {
                $hasLike = $em->getRepository('WebAwardsBundle:Heart')->verifyHasLike($idProject, $currentUser);
            }
        }

        //Recuperer dans la liste de tous les projets, le projet == meme id, order by date desc limit 1
        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'nbHeart'  => $nbHeart,
            'winner'   => $winner,
            'user'     => $user,
            'vote'     => $vote,
            'hasLike'   => $hasLike,
            'maxPages' => $maxPage,
            'thisPage' => $page,
        ));

    }
    /**
     * Init & show the junior's project.
     *
     * @Route("/{from}/", name="project_from")
     * @Method({"GET"})
     */
    public function getProjectFrom(Request $request){

        //Initialisation et récupération des variables
        $page =$request->query->get('page');
        $page = (int) $page;

        $page = $request->get('page');
        $from = $request->get('from');

        $em = $this->getDoctrine()->getManager();

        //Vérifie que la requête qui sera envoyée aura les bonnes data
        if( $from == "freelance" || $from == "agency" ||  $from == "junior"  || $from == "honorable") {

            $projects = $em->getRepository('WebAwardsBundle:Project')->getAllPostsFrom($page, $from);

        }
        
        if($from !== "honorable"){
            $totalPostsReturned = $projects->getIterator()->count();
            $totalProjects = $projects->count();
            $iterator = $projects->getIterator();
            $maxPage = ceil($totalProjects/9);
        } else {
            $totalProjects = count($projects);
            $maxPage = ceil($totalProjects/9);
        }

        return $this->render('project/showList.html.twig', array(
            'projects' => $projects,
            'maxPages' => $maxPage,
            'thisPage' => $page,
            'from'     => $from
        ));


    }

    /**
     * Init & show the winner's project.
     *
     * @Route("winner/{winner}/", name="project_from_winner")
     * @Method({"GET"})
     */
    public function getWinnerProjectFrom(Request $request){

        //Initialisation des variables
        $hasLike = "";
        $page = $request->get('page');
        $winner = $request->get('winner');
        $em = $this->getDoctrine()->getManager();

        //Recuperation des winner
        $projects = $em->getRepository('WebAwardsBundle:Project')->getWinnerProjects($page, $winner);
        
        //Pagination
        $totalPostsReturned = $projects->getIterator()->count();
        $totalProjects = $projects->count();
        $iterator = $projects->getIterator();
        $maxPage = ceil($totalProjects/9);



        $tabIdAuthor = [];
        $tabIdProject = [];
        foreach ($projects as $post) {
            $tabIdProject[] = $post->getId();
            $tabIdAuthor[] = $post->getIdAuthor();
        }
        
        if(count($projects) > 0){
            //Get the author of the project
            $idUser = $tabIdAuthor[0];
            $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);

            //Get project
            $idProject = $tabIdProject[0];
            //Get the vote of the project
            $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($idProject);
            $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);

            //Vérifie que l'utilisateur a voté
            $currentUser = $this->get('security.token_storage')->getToken()->getUser();
            if($currentUser != "anon.") {
                $roles = $currentUser->getRoles();
                if ($roles[0] != "ROLE_ADMIN") {
                    $hasLike = $em->getRepository('WebAwardsBundle:Heart')->verifyHasLike($idProject, $currentUser);
                }
            }

            return $this->render('project/showWinners.html.twig', array(
                'projects' => $projects,
                'user'     => $user,
                'nbHeart'  => $nbHeart,
                'vote'     => $vote,
                'hasLike'   => $hasLike,
                'maxPages' => $maxPage,
                'thisPage' => $page,
                'from'     => $winner
            ));
        }
        return $this->render('project/showWinners.html.twig', array(
            'projects' => $projects,
            'maxPages' => $maxPage,
            'thisPage' => $page,
            'from'     => $winner
        ));

    }

}
