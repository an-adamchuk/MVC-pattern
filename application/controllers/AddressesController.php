<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\JsonResponse;
use App\Core\Router;
use App\Models\AddressesModel;

/**
 * Class AddressesController to manage addresses.
 */
class AddressesController extends Controller
{

    /**
     * Show list of the addresses.
     */
    public function indexAction()
    {
        $addresses = $this->model->getAddresses();

        $response = new JsonResponse($addresses);
        $response->send();
    }

    /**
     * Create address.
     */
    public function createAction()
    {
        $address = new AddressesModel($this->request);
        $addressId = $address->save();

        $newAddress = $this->model->getAddress($addressId);
        $response = new JsonResponse($newAddress);
        $response->send();
    }

    /**
     * Get Address by id.
     *
     * @param $addressId
     */
    public function updateAction($addressId)
    {

        $address = $this->model->getAddress($addressId);

        if(!$address) {
            Router::ErrorResponse('Address not found.', 404);
            return;
        }

        $this->model->setAttributesValues($this->request);
        $this->model->update($addressId);

        $newAddress = $this->model->getAddress($addressId);
        $response = new JsonResponse($newAddress);
        $response->send();
    }

    /**
     * Update address.
     *
     * @param $addressId
     */
    public function showAction($addressId)
    {
        $address = $this->model->getAddress($addressId);

        if(!$address) {
            Router::ErrorResponse('Address not found.', 404);
            return;
        }

        $response = new JsonResponse($address);
        $response->send();
    }
}
