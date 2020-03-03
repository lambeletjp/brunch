<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Place;
use App\Form\Type\PlaceType;
use App\Repository\PlaceRepository;
use Geocoder\Provider\GoogleMaps\Model\GoogleAddress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/place")
 */
class PlaceController extends AbstractController
{

    /**
     * @Route("/", name="place_index", methods={"GET"})
     */
    public function index(PlaceRepository $placeRepository): Response
    {
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/new", name="place_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $place->getName();
            $street = $place->getAddress();
            $city = $place->getCity();
            $country = $place->getCountry();

            $currentAddress = $this->getGoogleAddress($name, $street, $city, $country);
            if($currentAddress->getStreetAddress() && $currentAddress->getStreetAddress()) {
                $place->setAddress($currentAddress->getStreetAddress() . ' ' . $currentAddress->getStreetNumber());
            }
            if($currentAddress->getLocality()) {
                $place->setCity($currentAddress->getLocality());
            }

            if($currentAddress->getPostalCode()) {
                $place->setPostalCode($currentAddress->getPostalCode());
            }

            if($currentAddress->getCoordinates()) {
                $place->setLongitude($currentAddress->getCoordinates()->getLongitude());
                $place->setLatitude($currentAddress->getCoordinates()->getLatitude());
            }

            if($currentAddress->getCountry()) {
                $place->setCountry($currentAddress->getCountry());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('place_show', [
                'id' => $place->getId(),
                'slug' => $place->getSlug()
            ]);
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/place/{slug}-{id}",
     *          requirements={
     *              "slug" = "[^/]+",
     *              "id" = "\d+"},
     *          name="place_show",
     *     methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function show(Place $place): Response
    {
        return $this->render('place/show.html.twig', [
            'place' => $place,
        ]);
    }

    /**
     * @Route("/place/{slug}-{id}/edit",
     *          requirements={
     *              "slug" = "[^/]+",
     *              "id" = "\d+"},
     *          name="place_edit",
     *     methods={"GET","POST"}
     *     )
     * @param $slug
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Place $place): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->savePlaceData($place);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('place_show', [
                'id' => $place->getId(),
                'slug' => $place->getSlug()
            ]);
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Place $place): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();
        }

        return $this->redirectToRoute('place_index');
    }

    /**
     * @Route("/find-location", name="find-location")
     * @method GET
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findLocationAction(Request $request)
    {
        $address = $request->get('address');
        $city  = $request->get('city');
        $country  = $request->get('country');
        $address = $address ? $address : $city . ' ' . $country;
        $currentAddress=null;
        if($address) {
            /** @var \Geocoder\Model\AddressCollection $addressCollection */
            $addressCollection = $this->getDoctrine()
                ->getRepository(Place::class)
                ->getGoogleAddress($address);

            $currentAddress = $addressCollection->first();
        }

        return $this->render('place/findLocation.html.twig', [
            'currentAddress' => $currentAddress
        ]);
    }


    /**
     * @Route("/quick-add-location", name="quick_add_location")
     * @method POST
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     */
    public function quickAddLocation(
        Request $request,
        \Swift_Mailer $mailer
    )
    {
        $name = $request->get('name');
        $street = $request->get('street');
        $city = $request->get('city');
        $country = $request->get('country');

        $currentAddress = $this->getGoogleAddress($name, $street, $city, $country);

        if(!$currentAddress){
            if(!$currentAddress) {
                $this->addFlash(
                    'notice',
                    'No place was found with the adresse : ' . $address
                );
                return $this->redirectToRoute($this->generateUrl('homepage'));
            }
        }

        $place = new Place();
        $place->setName($name);
        $place->setAddress($currentAddress->getStreetAddress() . ' ' . $currentAddress->getStreetNumber());
        $place->setCity($currentAddress->getLocality());
        $place->setPostalCode($currentAddress->getPostalCode());
        $place->setLongitude($currentAddress->getCoordinates()->getLongitude());
        $place->setLatitude($currentAddress->getCoordinates()->getLatitude());
        $place->setCountry($currentAddress->getCountry());

        $place = $this->savePlaceData($place);
        $this->sendNotificationNewPlace($mailer);
        return $this->redirect($this->generateUrl('place_edit',['slug' => $place->getSlug(), 'id' => $place->getId()]));
    }

    private function sendNotificationNewPlace(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Where-to-brunch'))
            ->setFrom('info@where-to-brunch.com')
            ->setTo('lambeletjp@gmail.com')
            ->setBody(
                'Somebody added a new place'
            );
        $mailer->send($message);
    }


    /**
     * @param Place $place
     * @return Place
     */
    public function savePlaceData(Place $place)
    {
        if(!$place->getLongitude() || !$place->getLatitude()) {
            /** @var \Geocoder\Model\AddressCollection $addressCollection */
            $addressCollection = $this->getDoctrine()
                ->getRepository(Place::class)
                ->getAddressCollection($place);

            /** @var \Geocoder\Model\Coordinates $address */
            if ($addressCollection->count() && $address = $addressCollection->get(0)) {
                $place->setLongitude($address->getLongitude());
                $place->setLatitude($address->getLatitude());
            }
        }


        $em = $this->getDoctrine()->getManager();
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $image */
        foreach ($place->getAllImages() as $image) {
            $imageFile = $image->getImageFile();
            if ($imageFile) {
                $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $image->setImageName($imageName);
                $em->persist($image);
                $em->flush();
            }
        }
        $em->persist($place);
        $em->flush();
        return $place;
    }

    /**
     * @param $name
     * @param $street
     * @param $city
     * @param $country
     * @return GoogleAddress
     */
    public function getGoogleAddress($name, $street, $city, $country)
    {
        $address = $name ? $name : '';
        $address = $street ? $address . ' ' . $street : $address;
        $address = $city ? $address . ' ' . $city : $address;
        $address = $country ? $address . ' ' . $country : $address;

        /** @var \Geocoder\Model\AddressCollection $addressCollection */
        $addressCollection = $this->getDoctrine()
            ->getRepository(Place::class)
            ->getGoogleAddress($address);

        /** @var GoogleAddress $currentAddress */
        $currentAddress = $addressCollection->first();

        if($currentAddress) {
            return $currentAddress;
        }

        $address = $street ? $street : $address;
        $address = $city ? $address . ' ' . $city: $address;
        $address = $country ? $address . ' ' . $country : $address;
        /** @var \Geocoder\Model\AddressCollection $addressCollection */
        $addressCollection = $this->getDoctrine()
            ->getRepository('AppBundle:Place')
            ->getGoogleAddress($address);

        $currentAddress = $addressCollection->first();
        return $currentAddress;
    }
}
