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
    public static function uploadFile(WebFile | array $file, String $title, String $description): Status
    {
        $file = is_array($file) ? new WebFile(obj: $file) : $file;
        $resource = new Resource();
        $resource->setPath("processing");
        $resource->setTitle($title);
        $resource->setDescription($description);
        $resource->setExtension($file->getExtension());
        $resource->store();
        $path = Utils::getBasePath() . "/" . self::RESOURCES_DIRECTORY . "/" . $resource->getId() . "." . $resource->getExtension();
        rename($file->getSystemPath(), $path);
        $resource->setPath($path);
        $resource->store();
        return new Status(isError: false,return: array($resource->toArray()), bareReturn: array($resource));
    }

    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     */
    public static function registerFile(String $url, String $title, String $description, String $extension): Status
    {
        $resource = new Resource();
        $resource->setPath($url);
        $resource->setTitle($title);
        $resource->setDescription($description);
        $resource->setExtension($extension);
        $resource->store();
        return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
    }

    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound|ReflectionException
     */
    public static function uploadAnimeVideo(WebFile | array $file, int $videoId): Status
    {
        $videos = Video::find(id: $videoId);
        if($videos->size() === 0){
            return new Status(isError: true, message: "Não foi possível encontrar o ID do vídeo.");
        }
        $file = is_array($file) ? new WebFile(obj: $file) : $file;
        $video = $videos[0];
        $resource = new Resource();
        $resource->setTitle($video->getTitle());
        $resource->setDescription($video->getAnime()?->getTitle() . " - " . $video->getSynopsis());
        $resource->setPath("processing");
        $resource->setExtension($file->getExtension());
        $resource->store();
        $path = Utils::correctURL(Utils::getBasePath() . "\\" . self::ANIME_VIDEOS_DIRECTORY . "\\" . $resource->getId() . "." . $file->getExtension());
        rename($file->getSystemPath(), $path);
        $resource->setPath($path);
        $resource->store();
        $video->setPath($resource);
        $video->store($video->getAnime());
        return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
    }
}