<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\Vote;
use WebAwardsBundle\Form\VoteType;

/**
 * Vote controller.
 *
 * @Route("/vote")
 */
class VoteController extends Controller
{
    /**
     * Lists all Vote entities.
     *
     * @Route("/", name="vote_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $votes = $em->getRepository('WebAwardsBundle:Vote')->findAll();

        return $this->render('vote/index.html.twig', array(
            'votes' => $votes,
        ));
    }

    /**
     * Creates a new Vote entity.
     *
     * @Route("/new", name="vote_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $vote = new Vote();
        $form = $this->createForm('WebAwardsBundle\Form\VoteType', $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vote);
            $em->flush();

            return $this->redirectToRoute('vote_show', array('id' => $vote->getId()));
        }

        return $this->render('vote/new.html.twig', array(
            'vote' => $vote,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Vote entity.
     *
     * @Route("/{id}", name="vote_show")
     * @Method("GET")
     */
    public function showAction(Vote $vote)
    {
        $deleteForm = $this->createDeleteForm($vote);

        return $this->render('vote/show.html.twig', array(
            'vote' => $vote,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Vote entity.
     *
     * @Route("/{id}/edit", name="vote_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vote $vote)
    {
        $deleteForm = $this->createDeleteForm($vote);
        $editForm = $this->createForm('WebAwardsBundle\Form\VoteType', $vote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vote);
            $em->flush();

            return $this->redirectToRoute('vote_edit', array('id' => $vote->getId()));
        }

        return $this->render('vote/edit.html.twig', array(
            'vote' => $vote,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Vote entity.
     *
     * @Route("/{id}", name="vote_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vote $vote)
    {
        $form = $this->createDeleteForm($vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vote);
            $em->flush();
        }

        return $this->redirectToRoute('vote_index');
    }

    /**
     * Creates a form to delete a Vote entity.
     *
     * @param Vote $vote The Vote entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vote $vote)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vote_delete', array('id' => $vote->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
