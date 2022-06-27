<?php

namespace APIObjects;

class Status
{
    private bool $isError;
    private ?string $message;
    private array $return;
    private mixed $bareReturn;

    /**
     * @param bool $isError
     * @param string|null $message
     * @param array $return
     * @param mixed|null $bareReturn
     */
    public function __construct(bool $isError, ?string $message = null, array $return = array(), mixed $bareReturn = array())
    {
        $this->isError = $isError;
        $this->message = $message;
        $this->return = $return;
        $this->bareReturn = $bareReturn;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isError;
    }

    /**
     * @param bool $isError
     * @return Status
     */
    public function setIsError(bool $isError): Status
    {
        $this->isError = $isError;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return Status
     */
    public function setMessage(?string $message): Status
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getReturn(): array
    {
        return $this->return;
    }

    /**
     * @param array $return
     * @return Status
     */
    public function setReturn(array $return): Status
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getBareReturn(): mixed
    {
        return $this->bareReturn;
    }

    /**
     * @param mixed|null $bareReturn
     * @return Status
     */
    public function setBareReturn(mixed $bareReturn): Status
    {
        $this->bareReturn = $bareReturn;
        return $this;
    }




}