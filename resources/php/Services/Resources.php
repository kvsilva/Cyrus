<?php

namespace Services;

use APIObjects\Status;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\NotNullable;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Utils;
use Objects\Resource;
use Objects\Video;

class Resources
{
    private const RESOURCES_DIRECTORY = "resources/site/resources";
    private const ANIME_VIDEOS_DIRECTORY = "resources/site/videos";

    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     */
    public static function uploadFile($file, String $title, String $description): Status
    {
        $resource = new Resource();
        $resource->setPath("none");
        $resource->setTitle($title);
        $resource->setDescription($description);
        $extension = explode(".", $file["name"]);
        $resource->setExtension($extension[count($extension)-1]);
        $resource->store();
        $tmp_name = $file["tmp_name"];
        $path = Utils::getBasePath() . self::RESOURCES_DIRECTORY . "/" . $resource->getId() . "." . $resource->getExtension();
        move_uploaded_file($tmp_name, $path);
        $resource->setPath($path);
        $resource->store();
        return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
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
     * @throws TableNotFound
     */
    public static function uploadAnimeVideo($file, Video $video): Status
    {
        $tmp_name = $file["tmp_name"];
        $extension = explode(".", $file["name"]);
        $extension = $extension[count($extension)-1];
        $path = Utils::getBasePath() . self::ANIME_VIDEOS_DIRECTORY . "/" . $video->getId() . "." . $extension;
        move_uploaded_file($tmp_name, $path);

        $resource = new Resource();
        $resource->setTitle($video->getTitle());
        $resource->setDescription($video->getAnime()?->getTitle() . " - " . $video->getSynopsis());
        $resource->setPath($path);
        $resource->setExtension($extension);
        $resource->store();
        return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
    }
}