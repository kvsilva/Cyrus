<?php

namespace APIObjects;

use Cassandra\Date;
use DateTime;
use JetBrains\PhpStorm\ArrayShape;

class Response
{

    private bool $status;
    private ?String $description;
    private array $details;
    private array $data;
    private array $errors;
    private array $dataTypes;

    /**
     * @param bool $status
     * @param string|null $description
     * @param array $details
     * @param array $data
     * @param array $errors
     * @param array $dataTypes
     */
    public function __construct(bool $status = true, ?string $description = null, array $details = array(), array $data = array(), array $errors = array(), array $dataTypes = array())
    {
        $this->status = $status;
        $this->setDescription($description);
        $this->data = $data;
        $this->details = $details;
        $this->errors = $errors;
        $this->dataTypes = $dataTypes;
    }

    /**
     * @param bool $print
     * @return bool|string
     */
    public function encode(bool $print = false): bool|string
    {
        $json = json_encode($this->toArray(), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        if($print){
            $this->publishHeader();
            echo $json;
        }
        return $json;
    }

    /**
     * @return void
     */
    public function publishHeader(){
        header('Content-type: application/json');
    }

    /**
     * @return array
     */
    #[ArrayShape(["status" => "bool", "description" => "null|String", "data" => "array"])]
    public function toArray() : array{
        $array = array(
            "status" => $this->status,
            "description" => $this->description,
            "timestamp" => (new DateTime())
        );
        if(sizeof($this->details) > 0){
            $array["details"] = array();
            foreach($this->details as $key => $value){
                $array["details"][$key] = $value;
            }
        }
        if(sizeof($this->errors) > 0) {
            $array["errors"] = array();
            foreach ($this->errors as $key => $value) {
                if (is_subclass_of($value, "Objects\Entity")) {
                    $array["errors"][$key] = $value->toArray();
                } else $array["errors"][$key] = $value;
            }
        }
        if(sizeof($this->data) > 0) {
            $array["data"] = array();
            foreach ($this->data as $key => $value) {
                if (is_subclass_of($value, "Objects\Entity")) {
                    $array["data"][$key] = $value->toArray();
                } else $array["data"][$key] = $value;
            }
        }
        if(sizeof($this->dataTypes) > 0) {
            $array["dataTypes"] = $this->dataTypes;
        }
        return $array;
    }



    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return Response
     */
    public function setStatus(bool $status): Response
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Response
     */
    public function setDescription(?string $description): Response
    {
        $this->description = $description == null ? ($this->status ? "The request was processed successfully." : "There was an error during your request, please check with an administrator.") : $description;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array|null $data
     * @return Response
     */
    public function setData(?array $data): Response
    {
        $this->data = $data == null ? array() : $data;
        return $this;
    }

    /**
     * @param null $data
     * @return Response
     */
    public function addData(mixed $data): Response
    {
        $this->data[] = $data;
        return $this;
    }

    /**
     * @param int $key
     * @return Response
     */
    public function removeData(int $key): Response
    {
        unset($this->data[$key]);
        return $this;
    }

    /**
     * @param mixed $detail
     * @param null $key
     * @return Response
     */
    public function addDetail(mixed $detail, $key = null): Response
    {
        if($key == null) $key = sizeof($this->details);
        $this->details[$key] = $detail;
        return $this;
    }

    /**
     * @param int $key
     * @return Response
     */
    public function removeDetail(mixed $key): Response
    {
        unset($this->details[$key]);
        return $this;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param array $details
     * @return Response
     */
    public function setDetails(array $details): Response
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return Response
     */
    public function setErrors(array $errors): Response
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return array
     */
    public function getDataTypes(): array
    {
        return $this->dataTypes;
    }

    /**
     * @param array $dataTypes
     * @return Response
     */
    public function setDataTypes(array $dataTypes): Response
    {
        $this->dataTypes = $dataTypes;
        return $this;
    }


}