<?php
/**
 * Created by PhpStorm.
 * User: lambeletjp
 * Date: 15/11/16
 * Time: 19:25
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Place;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class LandingPageController extends Controller
{

    /**
     * @Route("/c/{country}", name="landingPageCounty")
     */
    public function indexAction(Request $request)
    {
        $places = $this->getDoctrine()->getRepository('AppBundle:Place')->findBy(['approved' => 1],['id' => 'DESC'],10);
        return $this->render('AppBundle:Homepage:homepage.html.twig',['places' => $places]);
    }
}