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
        $em = $this->getDoctrine()->getManager();
        $vote = new Vote();
        $form = $this->createForm('WebAwardsBundle\Form\VoteType', $vote);
        $form->handleRequest($request);


        //Get current User id
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $idUser = $user->getId();

        //Get post data ( project id & author id )
        $idProject = $request->get('idProject');
        $idAuthor = $request->get('idAuthor');

        //Get User by Id
        $user = $em->getRepository('WebAwardsBundle:User')->findById($idUser);
        //Get project by Id
        $project = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);

        //Verify if the current user has been voted
        $hasVote = $em->getRepository('WebAwardsBundle:Vote')->VerifyUserVote($idUser, $idProject);
        if(!$hasVote){
            $this->addFlash(
                'notice',
                'Un vote a déjà été enregistré pour ce projet.'
            );
            return $this->redirectToRoute('project_show',  array('id' => $project[0]->getId()));

        }

        //Vérify if it's numeric data
        if( (!is_numeric($request->get('nbFluidity'))) || (!is_numeric($request->get('nbDesign')))
            || (!is_numeric($request->get('nbConcept'))) || (!is_numeric($request->get('nbResponsive'))) ) {

            $this->addFlash(
                'notice',
                'Le formulaire ne semble pas être valide !'
            );
            return $this->redirectToRoute('project_show',  array('id' => $project[0]->getId()));
        } else {
            //Vérify if data >=0 && data<=10
            if( (($request->get('nbFluidity') >= 10 || $request->get('nbFluidity') <= 0)) || (($request->get('nbDesign') >= 10 || $request->get('nbDesign') <= 0))
                || (($request->get('nbConcept') >= 10 || $request->get('nbConcept') <= 0)) || (($request->get('nbResponsive') >= 10 || $request->get('nbResponsive') <= 0))){
                $this->addFlash(
                    'notice',
                    'Seuls les votes de 0 à 10 sont autorisés !'
                );
                return $this->redirectToRoute('project_show',  array('id' => $project[0]->getId()));
            } else {

                //Init data
                $nbFluidity = (int) $request->get('nbFluidity');
                $nbDesign = (int) $request->get('nbDesign');
                $nbConcept = (int) $request->get('nbConcept');
                $nbResponsive = (int) $request->get('nbResponsive');
            }
        }



        //Calculate vote to prepare injection.
        $avgNbFluidity = ($nbFluidity/10)*15;
        $avgNbConcept = ($nbConcept/10)*15;
        $avgNbDesign = ($nbDesign/10)*30;
        $avgNbResponsive = ($nbResponsive/10)*40;
        $avgNbTotal = ($avgNbFluidity+$avgNbConcept+$avgNbDesign+$avgNbResponsive)/10;

        $avgNbTotal = round($avgNbTotal, 1, PHP_ROUND_HALF_DOWN);


        //If the current user != project author
        if($idUser != $idAuthor) {


            $vote->setIdUser($user[0]);
            $vote->setIdProject($project[0]);
            $vote->setNbFluidity($nbFluidity);
            $vote->setNbDesign($nbDesign);
            $vote->setNbConcept($nbConcept);
            $vote->setNbResponsive($nbResponsive);
            $vote->setNbTotal($avgNbTotal);

            if ($form->isSubmitted() && $form->isValid()) { //if the form is submit & valid

                $em->persist($vote);
                $em->flush();
                return $this->redirectToRoute('project_show', array('id' => $project[0]->getId()));

            } else {
                $this->addFlash(
                    'notice',
                    'Le formulaire ne semble pas être valide !'
                );
            }
        } else {
            $this->addFlash(
                'notice',
                'Vous ne pouvez pas voter pour votre propre projet !'
            );
        }

        return $this->redirectToRoute('project_show',  array('id' => $project[0]->getId()));
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
