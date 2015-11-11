<?php

namespace App\Core;

use Response;


/**
 * Class JsonResponse for manage json response.
 *
 * @package App\Core
 */
class JsonResponse extends Response
{
    protected $data;

    /**
     * Constructor.
     *
     * @param null $data
     * @param int $status
     * @throws \Exception
     */
    public function __construct($data = null, $status = 200)
    {
        parent::__construct('', $status);
        if (null === $data) {
            $data = new \ArrayObject();
        }

        $this->setData($data);
    }

    /**
     * Set json header.
     */
    private function setJsonHeader()
    {
        $this->headers['Content-Type'] = 'application/json';
    }

    /**
     * Set response data.
     *
     * @param array $data
     * @throws \Exception
     */
    private function setData($data = [])
    {
        try {
            $data = json_encode($data);
        } catch (\Exception $e) {
            throw $e;
        }

        $this->data = $data;
        $this->update();
    }

    /**
     * Update response.
     */
    protected function update()
    {
        $this->setJsonHeader();
        $this->setContent($this->data);
    }
}