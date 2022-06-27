<?php

namespace APIObjects;

class File
{
    private String $name;
    private String $type;
    private int $size;
    private String $tmp_name;
    private int $error;
    private String $full_path;

    /**
     * @param String $name
     * @param String $type
     * @param int $size
     * @param String $tmp_name
     * @param int $error
     * @param String $full_path
     */
    public function __construct(string $name, string $type, int $size, string $tmp_name, int $error, string $full_path)
    {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->tmp_name = $tmp_name;
        $this->error = $error;
        $this->full_path = $full_path;
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
     * @return File
     */
    public function setName(string $name): File
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
     * @return File
     */
    public function setType(string $type): File
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
     * @return File
     */
    public function setSize(int $size): File
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
     * @return File
     */
    public function setTmpName(string $tmp_name): File
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
     * @return File
     */
    public function setError(int $error): File
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
     * @return File
     */
    public function setFullPath(string $full_path): File
    {
        $this->full_path = $full_path;
        return $this;
    }


}