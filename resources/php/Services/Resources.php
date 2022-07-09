<?php

namespace Services;

use APIObjects\WebFile;
use APIObjects\Response;
use APIObjects\Status;
use Constants\API_MESSAGES;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Routing;
use Functions\Utils;
use Objects\Resource;
use Objects\Video;
use Objects\VideosArray;
use ReflectionException;

class Resources
{
    private const RESOURCES_DIRECTORY = "resources/site/resources";
    private const ANIME_VIDEOS_DIRECTORY = "resources/site/videos";
    public static function fTeste(mixed $blob) : Status{
        $controlFile = fopen(dirname(__DIR__) . "/../../dev/test_page/teste.txt", "w");
        fwrite($controlFile, $blob);
        fclose($controlFile);
        return new Status(isError: false);
    }
    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     */
    public static function uploadFile(WebFile | array $file): Status
    {
        $file = is_array($file) ? new WebFile(obj: $file) : $file;
        $resource = new Resource();
        $resource->setPath("processing");
        $resource->setOriginalName($file->getName());
        $resource->setExtension($file->getExtension());
        $resource->store();
        $path = Utils::getBasePath() . "/" . self::RESOURCES_DIRECTORY . "/" . $resource->getId() . "." . $resource->getExtension();
        rename($file->getSystemPath(), $path);
        $webPath = Routing::getRouting("root") . self::RESOURCES_DIRECTORY . "/" . $resource->getId() . "." . $resource->getExtension();
        $resource->setPath($webPath);
        $resource->store();
        return new Status(isError: false,return: array($resource->toArray(false, false)), bareReturn: array($resource));
    }

    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     */
    public static function registerFile(String $url, String $name, String $extension): Status
    {
        $resource = new Resource();
        $resource->setPath($url);
        $resource->setOriginalName($name);
        $resource->setExtension($extension);
        $resource->store();
        return new Status(isError: false, return: array($resource->toArray(false, false)), bareReturn: array($resource));
    }
}