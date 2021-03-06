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
     * Initialisation des données pour le footer (5 derniers projets / Projet du mois)
     * @return Project of the day and author of the project and the 5 last project
     */
    public function getFooterDataAction(){
        $em = $this->getDoctrine()->getManager();

        //Get 5 Last Project
        $lastProject = $em->getRepository('WebAwardsBundle:Project')->findBy(array('isVisible' => 1), array('dateAdd' => 'desc'), 5);

        //Get the Winner of the day
        $winners = $em->getRepository('WebAwardsBundle:Winner')->findBy(
            array('isDay' => '1')
        );
        //Get Project of the day
        foreach($winners as $win){
            $idProject = $win->getIdProject();
        }
        //Get Project
        $winner = $em->getRepository('WebAwardsBundle:Project')->findById($idProject);
        //Get User
        $userId = $winner[0]->getIdAuthor();
        $user = $em->getRepository('WebAwardsBundle:User')->findById($userId);

        //Get Winner of the month
        $monthWinners =  $em->getRepository('WebAwardsBundle:Winner')->findBy(
            array('isMonth' => '1')
        );
        //Get Project
        $monthWinner = $em->getRepository('WebAwardsBundle:Winner')->getLastMonthWinner($monthWinners);
        //Get Author Project
        $userMonthId = $monthWinner->getIdAuthor();
        $userMonthWinner = $em->getRepository('WebAwardsBundle:User')->findById($userMonthId);
        $userMonthWinner = $userMonthWinner[0];


        return $this->render('footer.html.twig', array(
            'monthWinner'=> $monthWinner,
            'userMonth'  => $userMonthWinner,
            'winner'     => $winner,
            'user'       => $user,
            'lastProject'=> $lastProject,
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

    /**
     * Set a Winner of the day entity.
     *
     * Need to call once a day
     * @Route("/setWinnerDay/", name="set_winner_day")
     *
     */
    public function setWinnerDayAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //Récupération des projets de la veille
        $yesterdayProjects = $em->getRepository('WebAwardsBundle:Project')->getProjectsFromYesterday();

        //Récupération du projet de la veille le mieux noté  
        $curr = 0;
        if($yesterdayProjects !== false){
            foreach ($yesterdayProjects as $project){
                $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($project->getId());
                if($vote !== null){
                    if($vote[0]->getNbTotal() > $curr){
                        $curr = $vote[0]->getNbTotal();
                        $winOftheDay = $project;
                    }
                }
            }

            //Initialise le nouveau winner
            $winner = new Winner();
            $winner->setIdProject($winOftheDay);
            $winner->setIsDay(true);
            $winner->setIsWeek(false);
            $winner->setIsMonth(false);
            $winner->setIsYear(false);

            //Récupération de l'ancien projet du jour gagnant
            $lastWinnerArr = $em->getRepository('WebAwardsBundle:Winner')->findBy(
                array('isDay' => '1')
            );

            $lastWinner = $lastWinnerArr[0];
            $lastWinner->setIsDay(false);

            //Suppression de l'ancien winner et ajout du nouveau  vers la DB
            $em->persist($lastWinner);
            $em->persist($winner);
            $em->flush();
        }

        return $this->redirectToRoute("homepage");

    }

    /**
     * Set a Winner of the week entity.
     *
     * Need to call once a week
     * @Route("/setWinnerWeek/", name="set_winner_week")
     *
     */

    public function setWinnerWeekAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //Récupération des projets de la veille
        $weekProjects = $em->getRepository('WebAwardsBundle:Project')->getProjectsFromLastWeek();

        $curr = 0;
        if($weekProjects !== false){
            foreach ($weekProjects as $project){
                $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($project->getId());
                if($vote !== null){
                    if($vote[0]->getNbTotal() > $curr){
                        $curr = $vote[0]->getNbTotal();
                        $winOfTheWeek = $project;
                    }
                }
            }


            $curr = 0;
            //Initialise le nouveau winner
            $winner = new Winner();
            $winner->setIdProject($winOfTheWeek);
            $winner->setIsDay(false);
            $winner->setIsWeek(true);
            $winner->setIsMonth(false);
            $winner->setIsYear(false);


            //Suppression de l'ancien winner et ajout du nouveau  vers la DB
            $em->persist($winner);
            $em->flush();
        }

        return $this->redirectToRoute("homepage");


    }

    /**
     * Set a Winner of the month entity.
     *
     * Need to call once a month
     * @Route("/setWinnerMonth/", name="set_winner_month")
     *
     */
    public function setWinnerMonthAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //Récupération des projets de la veille
        $monthProjects = $em->getRepository('WebAwardsBundle:Project')->getProjectsFromLastMonth();
        $curr = 0;
        if($monthProjects !== false){
            foreach ($monthProjects as $project){
                $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($project->getId());
                if($vote !== null){
                    if($vote[0]->getNbTotal() > $curr){
                        $curr = $vote[0]->getNbTotal();
                        $winOftheMonth = $project;
                    }
                }
            }

            //Initialise le nouveau winner
            $winner = new Winner();
            $winner->setIdProject($winOftheMonth);
            $winner->setIsDay(false);
            $winner->setIsWeek(false);
            $winner->setIsMonth(true);
            $winner->setIsYear(false);


            //Suppression de l'ancien winner et ajout du nouveau  vers la DB
            $em->persist($winner);
            $em->flush();
        }

        return $this->redirectToRoute("homepage");


    }

    /**
     * Set a Winner of the year entity.
     *
     * Need to call once a year
     * @Route("/setWinnerYear/", name="set_winner_year")
     *
     */
    public function setWinnerYearAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //Récupération des projets de la veille
        $yearProjects = $em->getRepository('WebAwardsBundle:Project')->getProjectsFromLastYear();


        //Recupère le projet le mieux noté
        $curr = 0;
        if($yearProjects !== false){
            foreach ($yearProjects as $project){
                $vote = $em->getRepository('WebAwardsBundle:Vote')->getAvgVotes($project->getId());
                if($vote !== null){
                    if($vote[0]->getNbTotal() > $curr){
                        $curr = $vote[0]->getNbTotal();
                        $winOfTheYear = $project;
                    }
                }
            }

            //Initialise le nouveau winner
            $winner = new Winner();
            $winner->setIdProject($winOfTheYear);
            $winner->setIsDay(false);
            $winner->setIsWeek(false);
            $winner->setIsMonth(false);
            $winner->setIsYear(true);


            //Suppression de l'ancien winner et ajout du nouveau  vers la DB
            $em->persist($winner);
            $em->flush();
        }

        return $this->redirectToRoute("homepage");


    }
}
