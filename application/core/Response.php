<?php

/**
 * Class Response to manage responses
 */
class Response
{
    /**
     * @var string
     */
    protected $content;
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $headers;

    /**
     * Constructor.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->headers = $headers;
        $this->setContent($content);
        $this->setStatusCode($status);
    }

    /**
     * Set response content.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Set response status code.
     *
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }


    /**
     * Sends content for the current web response.
     *
     * @return Response
     */
    public function sendContent()
    {
        echo $this->content;
        return $this;
    }

    /**
     * Set response headers.
     */
    public function sendHeaders()
    {
        // headers
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, false, $this->statusCode);
        }
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return Response
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }
}