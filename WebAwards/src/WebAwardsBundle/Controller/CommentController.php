<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\Comment;
use WebAwardsBundle\Form\CommentType;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all Comment entities.
     *
     * @Route("/", name="comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        //Si il n'est pas connecté
        if($currentUser == "anon."){
            return $this->redirectToRoute("homepage");
        }

        //Si ce n'est pas un admin
        $roles = $currentUser->getRoles();
        if($roles[0] != "ROLE_ADMIN" ){
            return $this->redirectToRoute("homepage");
        }

        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('WebAwardsBundle:Comment')->findAll();

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

    /**
     * Creates a new Comment entity.
     *
     * @Route("/new", name="comment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm('WebAwardsBundle\Form\CommentType', $comment);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();


        $idProject = $request->get('idProject');
        if( $idProject <= 0 || !is_numeric($idProject)){
            $this->addFlash(
                'notice',
                'Une erreur semble s\'etre produite'
            );
            return $this->redirectToRoute("homepage");
        }

        $idProject = (int) $request->get('idProject');
        $project = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);
        $project = $project[0];

        if($currentUser != "anon.") {
            $roles = $currentUser->getRoles();
            if ($roles[0] != "ROLE_ADMIN") {
                $idUser = $currentUser->getid();
            } else {
                $this->addFlash(
                    'notice',
                    'Un admin ne peut commenter un projet'
                );
                return $this->redirectToRoute("project_show", array( "id"=>$idProject));
            }
        } else {
            $this->addFlash(
                'notice',
                'Veuillez devez être connecté pour commenter un projet'
            );
            return $this->redirectToRoute("project_show", array( "id"=>$idProject));
        }


        $now = new \DateTime();
        $comment->setDateAdd($now);
        $comment->setIdProject($project);
        $comment->setIdUser($currentUser);
        $comment->setNbLike(0);


        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('project_show', array('id' => $idProject));
        }

        return $this->render('comment/new.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comment entity.
     *
     * @Route("/{id}", name="comment_show")
     * @Method("GET")
     */
    public function showAction(Comment $comment)
    {
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

        $deleteForm = $this->createDeleteForm($comment);

        return $this->render('comment/show.html.twig', array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment)
    {


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
            $currId = $currentUser->getId();
            $userIdComment = $comment->getIdUser()->getId();
            if($currId !== $userIdComment){
                dump($currId);
                dump($userIdComment);
                die();
                $this->addFlash(
                    'notice',
                    'Vous ne pouvez pas accéder à cette page'
                );
                return $this->redirectToRoute("homepage");
            }
        }

        $deleteForm = $this->createDeleteForm($comment);
        $editForm = $this->createForm('WebAwardsBundle\Form\CommentType', $comment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('comment_edit', array('id' => $comment->getId()));
        }

        return $this->render('comment/edit.html.twig', array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}", name="comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->createDeleteForm($comment);
        $form->handleRequest($request);

        $idProject = $comment->getIdProject()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }
        
        return $this->redirectToRoute('project_show', array('id' => $idProject));
    }

    /**
     * Creates a form to delete a Comment entity.
     *
     * @param Comment $comment The Comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
