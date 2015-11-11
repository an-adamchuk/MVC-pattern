<?php

namespace App\Core;
use PDO;


/**
 * Class Model
 * @package App\Core
 */
class Model
{
    /**
     * @var DB
     */
    public $db;
    private $query;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The model's attributes values.
     *
     * @var array
     */
    private $attributesValues = [];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model constructor.
     *
     * @param array $attributesValues
     */
    public function __construct(array $attributesValues = [])
    {
        $this->db = DB::getInstance()->getConnection();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (!empty($attributesValues)) {
            $this->setAttributesValues($attributesValues);
        }
    }

    /**
     * Prepare select query.
     *
     * @param $queryString
     * @param array $parameters
     * @return mixed
     */
    public function query($queryString, $parameters = [])
    {
        if(empty($parameters))
        {
            $this->query = $this->db->query($queryString);
        } else {
            $this->query = $this->db->prepare($queryString);
            $this->query->execute($parameters);
        }

        $this->query->setFetchMode(PDO::FETCH_ASSOC);

        return $this->query;
    }

    /**
     * Set model attributes.
     *
     * @param $attributesValues
     */
    public function setAttributesValues($attributesValues)
    {
        foreach ($this->attributes as $key => $rules) {
            if (array_key_exists($key, $attributesValues)) {

                if (array_key_exists('maxLength', $rules) &&
                    Model::checkMaxLength($attributesValues[$key], $rules['maxLength'])) {
                    Router::ErrorResponse('Field ' . $key . ' max length ' . $rules[maxLength] . '.');
                    die;
                }

                $this->attributesValues[$key] = $attributesValues[$key];
            } elseif (array_key_exists('nullable', $rules) && $rules['nullable'] == false) {
               Router::ErrorResponse('Field ' . $key . ' is required.');
               die;
            }
        }
    }

    /**
     * Save model.
     *
     * @return bool
     */
    public function save()
    {
        $keys = array_keys($this->attributesValues);
        $preparedKeysString = implode(',', $keys);
        $preparedKeysBind = str_replace(',', ',:', ':' . $preparedKeysString);

        try {
            $query = $this->db->prepare('INSERT INTO ' . $this->table . ' (' . $preparedKeysString . ') VALUES (' . $preparedKeysBind . ')');
            $query->execute($this->attributesValues);
        } catch(\Exception $e){
            Router::ErrorResponse('Invalid request');
            die;
        }

        return $this->db->lastInsertId();
    }

    /**
     * Update model.
     *
     * @param int $id
     * @return bool
     */
    public function update($id)
    {
        $keys = array_keys($this->attributesValues);

        $preparedKeys = array_map(function($key){
            return $key . '=:' . $key;
        }, $keys);
        $preparedKeysString = implode(', ', $preparedKeys);
        $this->attributesValues[$this->primaryKey] = (int) $id;


        try {
            $query = $this->db->prepare('UPDATE ' . $this->table . ' SET ' . $preparedKeysString . ' '.
                                            ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey);
            $query->execute($this->attributesValues);
        } catch(PDOException $e){
            Router::ErrorResponse('Invalid request.');
            die;
        }
    }

    public static function checkMaxLength($item, $maxLength)
    {
        return (strlen($item) > $maxLength);
    }
}