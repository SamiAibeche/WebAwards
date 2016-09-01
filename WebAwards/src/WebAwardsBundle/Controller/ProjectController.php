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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //Get All projects
        $projects = $em->getRepository('WebAwardsBundle:Project')->findAll();

        //Get the Winner of the day
        $winner = $em->getRepository('WebAwardsBundle:Winner')->findBy(
            array('isDay' => '1')
        );

        foreach($winner as $win){
            $idProject = $win->getIdProject();
        }
        $winner = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);
        $idUser = $winner[0]->getIdAuthor();
        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);
        $vote = $em->getRepository('WebAwardsBundle:Vote')->findByIdProject($idProject);

        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'winner'   => $winner,
            'user'     => $user,
            'vote'     => $vote,
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
        $deleteForm = $this->createDeleteForm($project);

        return $this->render('project/show.html.twig', array(
            'project' => $project,
            'delete_form' => $deleteForm->createView(),
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
               // unlink("./../web/uploads/project/".$mobile);
               // unlink("./../web/uploads/project/".$screen);
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
}
