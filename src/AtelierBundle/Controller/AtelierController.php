<?php

namespace AtelierBundle\Controller;

use AtelierBundle\Entity\Atelier;
use AtelierBundle\Entity\Participant;
use AtelierBundle\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AtelierController extends Controller
{

    public function readAction()
    {
        //create our entity manager: get the service doctrine
        $em=$this->getDoctrine();
        //repository help you fetch (read) entities of a certain class.
        $repository=$em->getRepository(Atelier::class);
        //find *all* 'Projet' objects
        $projets=$repository->findBy(array(), array('nbr' => 'ASC'));



        //render a template with the list of objects
        return $this->render('read.html.twig', array(
            'projets'=>$projets
        ));
    }

    public function deleteAction($id){
        //the manager is the responsible for saving objects, deleting and updating the  object
        $em=$this->getDoctrine()->getManager();
        $projet=$em->getRepository(Atelier::class)->find($id);
        //the remove() method notifies Doctrine that you'd like to remove the given object from the database
        $em->remove($projet);
        //The flush() method execute the DELETE query.
        $em->flush();
        //redirect our function to the read page to show our table
        return $this->redirectToRoute('read_projet');

    }
    public function detailAction(Request $request,$id)
    {
        $var =0;
        $exam = new Participant();
        $form = $this->createForm(ParticipantType::class, $exam);

        $form->handleRequest($request);
        $date1= date('Y-m-d H:i:s');
        $em = $this->getDoctrine()->getManager();

        $projet=$em->getRepository(Atelier::class)->find($id);

        $date2=$projet->getDateFin()->format('Y-m-d H:i:s');


        if (strtotime($date2) > strtotime($date1)  ){
            $var = 1;
        }

        if ($form->isSubmitted()) {

               $exam->setIda($projet);
               $projet->setNbr($projet->getNbr()+1);
               $em->persist($exam);

               $em->merge($projet);
               $em->flush();

        }

        //create our entity manager: get the service doctrine
        $em=$this->getDoctrine();
        //repository help you fetch (read) entities of a certain class.
        $repository=$em->getRepository(Atelier::class);
        //find *all* 'Projet' objects
        $projet=$repository->find($id);
        //render a template with the list of objects
        return $this->render('create.html.twig', array(
            'projets'=>$projet,
            "form"=>$form->createView(),
            'var' => $var)
        );



    }
}
