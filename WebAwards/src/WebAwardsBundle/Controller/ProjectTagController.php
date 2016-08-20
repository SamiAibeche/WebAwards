<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\ProjectTag;
use WebAwardsBundle\Form\ProjectTagType;

/**
 * ProjectTag controller.
 *
 * @Route("/projecttag")
 */
class ProjectTagController extends Controller
{
    /**
     * Lists all ProjectTag entities.
     *
     * @Route("/", name="projecttag_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectTags = $em->getRepository('WebAwardsBundle:ProjectTag')->findAll();

        return $this->render('projecttag/index.html.twig', array(
            'projectTags' => $projectTags,
        ));
    }

    /**
     * Creates a new ProjectTag entity.
     *
     * @Route("/new", name="projecttag_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projectTag = new ProjectTag();
        $form = $this->createForm('WebAwardsBundle\Form\ProjectTagType', $projectTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTag);
            $em->flush();

            return $this->redirectToRoute('projecttag_show', array('id' => $projectTag->getId()));
        }

        return $this->render('projecttag/new.html.twig', array(
            'projectTag' => $projectTag,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ProjectTag entity.
     *
     * @Route("/{id}", name="projecttag_show")
     * @Method("GET")
     */
    public function showAction(ProjectTag $projectTag)
    {
        $deleteForm = $this->createDeleteForm($projectTag);

        return $this->render('projecttag/show.html.twig', array(
            'projectTag' => $projectTag,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ProjectTag entity.
     *
     * @Route("/{id}/edit", name="projecttag_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProjectTag $projectTag)
    {
        $deleteForm = $this->createDeleteForm($projectTag);
        $editForm = $this->createForm('WebAwardsBundle\Form\ProjectTagType', $projectTag);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTag);
            $em->flush();

            return $this->redirectToRoute('projecttag_edit', array('id' => $projectTag->getId()));
        }

        return $this->render('projecttag/edit.html.twig', array(
            'projectTag' => $projectTag,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ProjectTag entity.
     *
     * @Route("/{id}", name="projecttag_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProjectTag $projectTag)
    {
        $form = $this->createDeleteForm($projectTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projectTag);
            $em->flush();
        }

        return $this->redirectToRoute('projecttag_index');
    }

    /**
     * Creates a form to delete a ProjectTag entity.
     *
     * @param ProjectTag $projectTag The ProjectTag entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProjectTag $projectTag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projecttag_delete', array('id' => $projectTag->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
