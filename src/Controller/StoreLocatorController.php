<?php

namespace Mablae\StoreLocatorBundle\Controller;

use Geocoder\Model\Coordinates;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreLocatorController extends Controller
{

    /**
     * @Route(name="mablae.store_locator.list", path="/list", methods={"GET"})
     *
     * @return Response
     */
    public function listAction() {

        $storeListProvider = $this->get('mablae.store_locator.store_list_provider');
        $storeList = $storeListProvider->findAll();

        $serializer = $this->get('serializer');

        return new Response(
            $serializer->serialize($storeList, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route(name="mablae.store_locator.locate_by_searchTerm", path="/locateBySearchTerm", methods={"POST"})
     *
     * @return Response
     */
    public function locateBySearchTermAction(string $searchTerm) {

        $storeLocator = $this->get('mablae.store_locator');
        $locatedStoreList = $storeLocator->locateBySearchTerm($searchTerm);

        return $this->buildResponse($locatedStoreList);
    }
    /**
     * @Route(name="mablae.store_locator.locate_by_coordinates", path="/locateByCoordinates", methods={"POST"})
     *
     * @return Response
     */
    public function locateByCoordinatesAction(string $latitude, string $longitude) {

        $storeLocator = $this->get('mablae.store_locator');
        $locatedStoreList = $storeLocator->locateByCoordinate(new Coordinates((float)$latitude, (float)$longitude));

        return $this->buildResponse($locatedStoreList);
    }

    /**
     * @Route(name="mablae.store_locator.locate_by_ip", path="/locateByIp", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function locateByIpAction(Request $request) {

        $storeLocator = $this->get('mablae.store_locator');

        $ipAddress = $request->getClientIp();
        $locatedStoreList = $storeLocator->locateByIpAddress($ipAddress);

        return $this->buildResponse($locatedStoreList);
    }

    /**
     * @param $locatedStoreList
     * @return Response
     */
    private function buildResponse($locatedStoreList): Response
    {
        $serializer = $this->get('serializer');

        return new Response(
            $serializer->serialize($locatedStoreList, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
