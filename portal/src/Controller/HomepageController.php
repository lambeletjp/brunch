<?php
/**
 * Created by PhpStorm.
 * User: Jean-Pierre Lambelet (jlambelet@contentfleet.com)
 * Date: 12/03/16
 * Time: 16:10
 */

namespace App\Controller;

use App\Entity\Place;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $places = $this->getDoctrine()->getRepository(Place::class)->findBy(['approved' => 1],['id' => 'DESC'],10);
        return $this->render('homepage/homepage.html.twig',['places' => $places]);
    }
}
