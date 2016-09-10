<?php

namespace WebAwardsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebAwardsBundle\Entity\Heart;
use WebAwardsBundle\Form\HeartType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Heart controller.
 *
 * @Route("/heart")
 */
class HeartController extends Controller
{

    /**
     * Creates a new Heart entity.
     *
     * @Route("/new/{idProject}/", name="heart_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();


        //Redirige si un utilisateur ananyme tente de liker un projet
        if($currentUser == "anon."){
            $this->addFlash(
                'notice',
                'Vous aimez ce projet ? Connectez-vous afin d\'augmenter son score !'
            );
            return $this->redirectToRoute("homepage");
        }

        //Redirige si un admin tente de liker un projet
        $roles = $currentUser->getRoles();
        if($roles[0] == "ROLE_ADMIN" ){
            $this->addFlash(
                'notice',
                'Un admin ne peut participer !'
            );
            return $this->redirectToRoute("homepage");
        }

        //Récupération de l'id du project
        $idProject = (int) $request->get('idProject');

        //Récupération du projet
        $project = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);
        $project = $project[0];

        //Vérifie si l'utilisateur a déjà liker le projet
        $hasLike = $em->getRepository('WebAwardsBundle:Heart')->verifyHasLike($idProject, $currentUser);


        if(!$hasLike){ //Si il l'a déjà liké

            //Récupère l'objet
            $heart = $em->getRepository('WebAwardsBundle:Heart')->getHeart($idProject, $currentUser);

            //Supprimer le like de la DB
            $em->remove($heart);
            $em->flush();

        } else { //Sinon

            //Création d'un nouveau like
            $heart = new Heart();

            $heart->setIdProject($project);
            $heart->setIdUser($currentUser);
            $heart->setIsLike(true);

            //Injéction du like dans la DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($heart);
            $em->flush();

        }
        //Retour vers la page précédente
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
    
}
