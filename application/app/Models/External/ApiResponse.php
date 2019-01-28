<?php
namespace App\Models\External;

use Illuminate\Support\Collection;

/**
 * For External APIs
 */
class ApiResponse
{
    private $headers = [];
    private $content = [];
    private $status_code;
    private $has_error = false;
    
    public function parse(\GuzzleHttp\Psr7\Response $response): ApiResponse
    {
        $this->setHeaders($response->getHeaders());
        $this->setStatusCode($response->getStatusCode());

        if ($this->getStatusCode() < 200 || $this->getStatusCode() >= 300) {
            $this->setHasError(true);
        }

        $this->setContent(
            collect(
                json_decode($response->getBody()->getContents(), true)
            )
        );

        return $this;
    }
    
    public function hasError()
    {
        return $this->has_error;
    }
    
    public function wasSuccessful()
    {
        return ($this->getStatusCode() >= 200 && $this->getStatusCode() < 300 && !$this->hasError()) ? true : false;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent(Collection $content)
    {
        $this->content = $content;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function setStatusCode(int $status_code)
    {
        $this->status_code = $status_code;
    }

    public function setHasError(bool $has_error)
    {
        $this->has_error = $has_error;
    }
}
