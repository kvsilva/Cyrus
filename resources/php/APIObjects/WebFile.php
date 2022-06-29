<?php

namespace APIObjects;

class WebFile
{
    private String $name;
    private String $type;
    private int $size;
    private String $tmp_name;
    private int $error;
    private String $full_path;
    private String $extension;
    private String $system_path;
    private String $web_path;

    /**
     * @param array|null $obj
     * @param string|null $name
     * @param string|null $type
     * @param int|null $size
     * @param string|null $tmp_name
     * @param int|null $error
     * @param string|null $full_path
     * @param string|null $extension
     * @param string|null $system_path
     * @param string|null $web_path
     */
    public function __construct(?array $obj = null, ?string $name = null, ?string $type = null, ?int $size = null, ?string $tmp_name = null, ?int $error = null, ?string $full_path = null, ?string $extension = null, ?string $system_path = null, ?string $web_path = null)
    {
        if($obj !== null){
            $this->name = $obj["name"];
            $this->type = $obj["type"];
            $this->size = $obj["size"];
            $this->tmp_name = $obj["tmp_name"];
            $this->error = $obj["error"];
            $this->full_path = $obj["full_path"];
            $this->extension = $obj["extension"];
            $this->system_path = $obj["system_path"];
            $this->web_path = $obj["web_path"];
        } else {
            $this->name = $name;
            $this->type = $type;
            $this->size = $size;
            $this->tmp_name = $tmp_name;
            $this->error = $error;
            $this->full_path = $full_path;
            $this->extension = $extension;
            $this->system_path = $system_path;
            $this->web_path = $web_path;
        }
    }


    /**
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param String $name
     * @return WebFile
     */
    public function setName(string $name): WebFile
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return String
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param String $type
     * @return WebFile
     */
    public function setType(string $type): WebFile
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return WebFile
     */
    public function setSize(int $size): WebFile
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return String
     */
    public function getTmpName(): string
    {
        return $this->tmp_name;
    }

    /**
     * @param String $tmp_name
     * @return WebFile
     */
    public function setTmpName(string $tmp_name): WebFile
    {
        $this->tmp_name = $tmp_name;
        return $this;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @param int $error
     * @return WebFile
     */
    public function setError(int $error): WebFile
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return String
     */
    public function getFullPath(): string
    {
        return $this->full_path;
    }

    /**
     * @param String $full_path
     * @return WebFile
     */
    public function setFullPath(string $full_path): WebFile
    {
        $this->full_path = $full_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getSystemPath(): string
    {
        return $this->system_path;
    }

    /**
     * @param string $system_path
     * @return WebFile
     */
    public function setSystemPath(string $system_path): WebFile
    {
        $this->system_path = $system_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getWebPath(): string
    {
        return $this->web_path;
    }

    /**
     * @param string $web_path
     * @return WebFile
     */
    public function setWebPath(string $web_path): WebFile
    {
        $this->web_path = $web_path;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return WebFile
     */
    public function setExtension(string $extension): WebFile
    {
        $this->extension = $extension;
        return $this;
    }


}