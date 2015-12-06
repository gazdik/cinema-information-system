<?php
/**
 * Created by PhpStorm.
 * User: marek
 * Date: 20/11/15
 * Time: 18:21
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class XMLOutputController extends Controller
{
    /**
     * @Route("/xml", defaults={"_format"="xml"}, name="xmlRoute")
     */
    public function xmlAction() {

        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime();

        $projections = $em->getRepository('AppBundle:Projection')
            ->findFromToOrdered($date, $date->modify('+1 days') );


        return $this->render('xmlOutput.xml.twig', array(
            'projections' => $projections,
        ));
    }


}