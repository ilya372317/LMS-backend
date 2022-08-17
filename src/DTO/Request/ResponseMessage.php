<?php

namespace App\DTO\Request;

class ResponseMessage
{
    private int $responseCode;
    private string $title;
    private string $message;

    public function __construct(int $responseCode, string $title, string $message)
    {
        $this->responseCode = $responseCode;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @param  int  $responseCode
     * @return ResponseMessage
     */
    public function setResponseCode(int $responseCode): ResponseMessage
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     * @return ResponseMessage
     */
    public function setTitle(string $title): ResponseMessage
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param  string  $message
     * @return ResponseMessage
     */
    public function setMessage(string $message): ResponseMessage
    {
        $this->message = $message;
        return $this;
    }


}