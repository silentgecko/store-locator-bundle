<?php

namespace Silentgecko\StoreLocatorBundle\Controller;

use Geocoder\Model\Coordinates;
use Silentgecko\StoreLocator\StoreList\InMemoryStoreListProvider;
use Silentgecko\StoreLocator\StoreLocator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class StoreLocatorController extends AbstractController
{
    private StoreLocator $storeLocator;
    private Serializer $serializer;

    public function __construct(StoreLocator $storeLocator, Serializer $serializer)
    {
        $this->storeLocator = $storeLocator;
        $this->serializer = $serializer;
    }

    /**
     * @Route(name="mablae.store_locator.list", path="/list", methods={"GET"})
     *
     * @return Response
     */
    public function listAction(InMemoryStoreListProvider $listProvider)
    {
        $storeList = $listProvider->findAll();

        return new Response(
            $this->getSerializer()->serialize($storeList, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @Route(name="mablae.store_locator.locate_by_searchTerm", path="/locateBySearchTerm", methods={"POST"})
     *
     * @return Response
     */
    public function locateBySearchTermAction(string $searchTerm)
    {
        $locatedStoreList = $this->getStoreLocator()->locateBySearchTerm($searchTerm);

        return $this->buildResponse($locatedStoreList);
    }

    /**
     * @Route(name="mablae.store_locator.locate_by_coordinates", path="/locateByCoordinates", methods={"POST"})
     *
     * @return Response
     */
    public function locateByCoordinatesAction(string $latitude, string $longitude)
    {
        $locatedStoreList = $this->getStoreLocator()->locateByCoordinate(new Coordinates((float)$latitude, (float)$longitude));

        return $this->buildResponse($locatedStoreList);
    }

    /**
     * @Route(name="mablae.store_locator.locate_by_ip", path="/locateByIp", methods={"GET"})
     *
     * @return Response
     */
    public function locateByIpAction(Request $request)
    {
        $ipAddress = $request->getClientIp();
        $locatedStoreList = $this->getStoreLocator()->locateByIpAddress($ipAddress);

        return $this->buildResponse($locatedStoreList);
    }

    /**
     * @param $locatedStoreList
     */
    private function buildResponse($locatedStoreList): Response
    {
        return new Response(
            $this->getSerializer()->serialize($locatedStoreList, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function getStoreLocator(): StoreLocator
    {
        return $this->storeLocator;
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
