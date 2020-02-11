<?php

namespace AtelierBundle\Controller;

use AtelierBundle\Entity\Participant;
use AtelierBundle\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ParticipantController extends Controller
{

    public function createAction(Request $request)
    {
        $exam = new Participant();
        $form = $this->createForm(ParticipantType::class, $exam);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exam);
            $em->flush();

        }
        return $this->render("create.html.twig",array("form"=>$form->createView()));
    }
}
