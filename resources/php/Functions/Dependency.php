<?php

namespace Functions;

use JetBrains\PhpStorm\Pure;
use Others\Routing;

class Dependency
{

    private String $directory;
    private ?String $version;
    private String $location;
    private array $import = array();

    /**
     * @param string $directory
     * @param string|null $version
     * @param string $location
     */
    public function __construct(string $directory, string $location, string $version = null)
    {
        $this->directory = $directory;
        $this->version = $version;
        $this->setLocation($location);
    }

    /**
     * @return String|null
     */
    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    /**
     * @param String|null $directory
     * @return Dependency
     */
    public function setDirectory(?string $directory): Dependency
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param String|null $version
     * @return Dependency
     */
    public function setVersion(?string $version): Dependency
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Dependency
     */
    public function setLocation(string $location): Dependency
    {
        if(!str_ends_with($location, "/") && str_ends_with($location, "\\")) $location = $location . "/";
        $location = str_replace("\\", "/", $location);
        $this->location = $location;
        return $this;
    }


    /**
     * @param string $extension
     * @return String|null
     */
    #[Pure] public function getImport(string $extension = "js"): ?String
    {
        return isset($this->import[$extension]) ? $this->location . $this->getDirectory() . ($this->getVersion() != null ? ("/" . $this->getVersion()) : "") . "/"  . $this->import[$extension] : null;
    }

    /**
     * @param string $path
     * @param string $extension
     * @return Dependency
     */
    public function addImport(string $path, string $extension = "js"): Dependency
    {
        if($path == null || $extension == null) return $this;
        $this->import[$extension] = $path;
        return $this;
   }

}