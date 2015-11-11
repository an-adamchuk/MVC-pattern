<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class AddressesModel
 * @package App\Models
 */
class AddressesModel extends Model
{
    protected $table = 'ADDRESS';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ADDRESSID';

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
    'LABEL' => [
        'nullable' => false,
        'maxLength' => 100,
    ],
    'STREET' => [

            'nullable' => false,
            'maxLength' => 100,
        ],
    'HOUSENUMBER' => [

            'nullable' => false,
            'maxLength' => 10,
        ],
    'POSTALCODE' => [

            'nullable' => false,
            'maxLength' => 6,
        ],
    'CITY' => [

            'nullable' => false,
            'maxLength' => 100,
        ],
    'COUNTRY' => [

            'nullable' => false,
            'maxLength' => 100,
        ],
    ];

    public function getAddresses()
    {
        return $this->query('SELECT * FROM ADDRESS')->fetchAll();
    }

    public function getAddress($addressId)
    {
        return $this->query(
                            'SELECT * FROM ADDRESS WHERE '. $this->primaryKey .' = :' . $this->primaryKey,
                            [$this->primaryKey => $addressId])
                    ->fetch();
    }
}