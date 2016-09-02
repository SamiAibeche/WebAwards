<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\Winner;
use WebAwardsBundle\Form\WinnerType;

/**
 * Winner controller.
 *
 * @Route("/winner")
 */
class WinnerController extends Controller
{
    /**
     * Lists all Winner entities.
     *
     * @Route("/", name="winner_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $winners = $em->getRepository('WebAwardsBundle:Winner')->findAll();

        return $this->render('winner/index.html.twig', array(
            'winners' => $winners,
        ));
    }

    /**
     * Creates a new Winner entity.
     *
     * @Route("/new", name="winner_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $winner = new Winner();
        $form = $this->createForm('WebAwardsBundle\Form\WinnerType', $winner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($winner);
            $em->flush();

            return $this->redirectToRoute('winner_show', array('id' => $winner->getId()));
        }

        return $this->render('winner/new.html.twig', array(
            'winner' => $winner,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Winner entity.
     *
     * @Route("/{id}", name="winner_show")
     * @Method("GET")
     */
    public function showAction(Winner $winner)
    {
        $deleteForm = $this->createDeleteForm($winner);

        return $this->render('winner/show.html.twig', array(
            'winner' => $winner,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Winner entity.
     *
     * @Route("/{id}/edit", name="winner_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Winner $winner)
    {
        $deleteForm = $this->createDeleteForm($winner);
        $editForm = $this->createForm('WebAwardsBundle\Form\WinnerType', $winner);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($winner);
            $em->flush();

            return $this->redirectToRoute('winner_edit', array('id' => $winner->getId()));
        }

        return $this->render('winner/edit.html.twig', array(
            'winner' => $winner,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @return Project of the day
     */
    public function winnerDayAction(){
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
        $userId = $winner[0]->getIdAuthor();
        $user = $em->getRepository('WebAwardsBundle:User')->findById($userId);


        return $this->render('footer.html.twig', array(
            'winner'   => $winner,
            'user'     => $user
        ));
    }

    /**
     * Deletes a Winner entity.
     *
     * @Route("/{id}", name="winner_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Winner $winner)
    {
        $form = $this->createDeleteForm($winner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($winner);
            $em->flush();
        }

        return $this->redirectToRoute('winner_index');
    }

    /**
     * Creates a form to delete a Winner entity.
     *
     * @param Winner $winner The Winner entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Winner $winner)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('winner_delete', array('id' => $winner->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
