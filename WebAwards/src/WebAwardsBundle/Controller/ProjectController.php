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
        //Get All projects
        //$projects = $em->getRepository('WebAwardsBundle:Project')->findByIsVisible(1);
        //$projects = $em->getRepository('WebAwardsBundle:Project')->findBy(array('isVisible' => 1), array('dateAdd' => 'desc'));
        
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

        $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);

        //Get the last project of the Month

        //All Winner of the month
        //Recuperer dans la liste de tous les projets, le projet == meme id, order by date desc limit 1
        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'winner'   => $winner,
            'nbHeart'  => $nbHeart,
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
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($project);
        $form = $this->createForm('WebAwardsBundle\Form\VoteType');

        $user = $project->getIdAuthor();
        $idUser = $user->getId();
        $idProject = $project->getId();


        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);

        //Get the vote of the project
        $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($idProject);
        $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);
        //dump($vote);
        //die();

        return $this->render('project/show.html.twig', array(
            'project' => $project,
            'user'     => $user,
            'nbHeart' => $nbHeart,
            'vote'     => $vote,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView()
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
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();


        if($currentUser == "anon."){
            $this->addFlash(
                'notice',
                'Vous ne pouvez pas accéder à cette page'
            );
                return $this->redirectToRoute("homepage");
        }
        
        $roles = $currentUser->getRoles();
        if($roles[0] != "ROLE_ADMIN" ){
                $this->addFlash(
                    'notice',
                    'Vous ne pouvez pas accéder à cette page'
                );
                return $this->redirectToRoute("homepage");
        }

        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createForm('WebAwardsBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //Récupération des images
            $fileScreen = $project->getImgScreen();
            $fileMobile = $project->getImgMobile();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_edit', array('id' => $project->getId()));
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

            //Vérification de l'existance des images
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
        //Recuperer dans la liste de tous les projets, le projet == meme id, order by date desc limit 1
        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'nbHeart'  => $nbHeart,
            'winner'   => $winner,
            'user'     => $user,
            'vote'     => $vote,
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

        $page =$request->query->get('page');
        $page = (int) $page;

        $em = $this->getDoctrine()->getManager();

        $page = $request->get('page');
        $from = $request->get('from');

        if( $from == "freelance" || $from == "agency" ||  $from == "junior"  || $from == "honorable") {

            $projects = $em->getRepository('WebAwardsBundle:Project')->getAllPostsFrom($page, $from);

        }

        $totalPostsReturned = $projects->getIterator()->count();
        $totalProjects = $projects->count();

        $iterator = $projects->getIterator();
        $maxPage = ceil($totalProjects/9);

        return $this->render('project/showList.html.twig', array(
            'projects' => $projects,
            'maxPages' => $maxPage,
            'thisPage' => $page,
            'from'     => $from
        ));

    }

    /**
     * Init & show the junior's project.
     *
     * @Route("winner/{winner}/", name="project_from_winner")
     * @Method({"GET"})
     */
    public function getWinnerProjectFrom(Request $request){

        $page =$request->query->get('page');
        $page = (int) $page;

        $em = $this->getDoctrine()->getManager();

        $page = $request->get('page');
        $winner = $request->get('winner');
        
        $projects = $em->getRepository('WebAwardsBundle:Project')->getWinnerProjects($page, $winner);
        

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

        //Get the author of the project
        $idUser = $tabIdAuthor[0];
        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);

        //Get the vote of the project
        $idProject = $tabIdProject[0];

        $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($idProject);

        $nbHeart = $em->getRepository('WebAwardsBundle:Heart')->getNbHeart($idProject);


        return $this->render('project/showWinners.html.twig', array(
            'projects' => $projects,
            'user'     => $user,
            'nbHeart'  => $nbHeart,
            'vote'     => $vote,
            'maxPages' => $maxPage,
            'thisPage' => $page,
            'from'     => $winner
        ));

    }

}
