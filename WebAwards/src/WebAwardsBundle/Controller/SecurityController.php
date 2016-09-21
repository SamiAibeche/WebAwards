<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render(
            'login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/admin", name="admin_action")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    /**
     * @Route("/admin/navigation", name="admin_navigation")
     */
    public function getNavigationPageAction(){
        $em = $this->getDoctrine()->getManager();
        $projectToValid = $em->getRepository('WebAwardsBundle:Project')->getInactifProjects();

        $nbProject = count($projectToValid);
        
        return $this->render('admin/navigation.html.twig', array(
            'nbProject' => $nbProject
        ));
    }

    /**
     * @Route("/admin/project/validate", name="admin_inactif")
     */
    public function getInactifProjectPageAction(){
        $em = $this->getDoctrine()->getManager();
        $projectToValid = $em->getRepository('WebAwardsBundle:Project')->getInactifProjects();

        $projects = $projectToValid;

        return $this->render('admin/project_inactif.html.twig', array(
            'projects' => $projects
        ));
    }
    /**
     * @Route("/admin/project/activate/{id}/", name="admin_active_project")
     */
    public function setActiveProjectAction($id){


        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('WebAwardsBundle:Project')->findById($id);
        $project = $project[0];
       // $resp = $em->getRepository('WebAwardsBundle:Project')->setProjectActive($id);

        $project->setIsVisible(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();


        $this->addFlash(
            'notice',
            'Modifications enrgistrées.'
        );

        return $this->redirectToRoute("admin_inactif");
    }
    /**
     * @Route("/admin/project/{id}/validate", name="admin_invalid_show")
     */
    public function showInactifProjectAction($id){


        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('WebAwardsBundle:Project')->findById($id);
        $userId = $project[0]->getIdAuthor();
        $user = $em->getRepository('WebAwardsBundle:User')->findById($userId);

        $user = $user[0];
        $project = $project[0];

        return $this->render('admin/show_inactif.html.twig', array(
            'project' => $project,
            'user' => $user
        ));
    }

    /**
     *
     * @Route("/admin/project/{diff}", name="admin_project_index")
     */
    public function getAllProjectAction($diff = null, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('WebAwardsBundle:Project')->getAllPostsForAdmin($page);
        $totalPostsReturned = $projects->getIterator()->count();
        $totalProjects = $projects->count();
        $maxPage = ceil($totalProjects/6);
        $iterator = $projects->getIterator();


        return $this->render('admin/project.html.twig', array(
            'projects' => $projects,
            'maxPages' => $maxPage,
            'thisPage' => $page,
        ));
    }

    /**
     * Displays the number of project present in the page and set the navigation
     *
     * @Route("admin/project/", name="admin_project_list")
     */
    public function listAction(Request $request, $order = "vide"){

        $em = $this->getDoctrine()->getManager();

        $page = $request->get('page');

        $projects = $em->getRepository('WebAwardsBundle:Project')->getAllPostsForAdmin($page);
        $totalPostsReturned = $projects->getIterator()->count();
        $totalProjects = $projects->count();
        $iterator = $projects->getIterator();
        $maxPage = ceil($totalProjects/6);

        //Get the Winner of the day

        //Recuperer dans la liste de tous les projets, le projet == meme id, order by date desc limit 1
        return $this->render('admin/project_list.html.twig', array(
            'projects' => $projects,
            'maxPages' => $maxPage,
            'thisPage' => $page,
        ));

    }
}
