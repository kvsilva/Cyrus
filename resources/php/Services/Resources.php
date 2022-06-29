<?php

namespace Services;

use APIObjects\File;
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
     * @throws ReflectionException
     */
    public static function uploadFile(array $files, String $title, String $description): Status
    {
        $controlFile = fopen(dirname(__DIR__) . "/../../dev/test_page/teste.txt", "w");
        ob_flush();
        ob_start();
        var_dump($files);
        fwrite($controlFile, ob_get_flush());
        fclose($controlFile);
        $status = array();
        $file = "files";
        for($i = 0; $i < count($_FILES[$file]["name"]); $i++) {
            $status[$i] = self::upload(method: "upload-file", file: $file, filePos: $i, title: $title, description: $description);
            if($status[$i]->isError()){
                return $status[$i];
            }
        }
        $ret = array();
        $original = array();
        foreach($status as $item){
            $ret[] = $item->getReturn()[0];
            $original = $item->getBareReturn()[0];
        }
        return new Status(isError: false, return: array($ret), bareReturn: array($original));
    }

    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     * @throws ReflectionException
     */
    public static function registerFile(String $url, String $title, String $description, String $extension): Status
    {
        return self::upload(method: "register-file", file: $url, title: $title, description: $description, extension: $extension);
    }

    /**
     * @throws NotNullable
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound|ReflectionException
     */
    public static function uploadAnimeVideo(String $file, int $videoId): Status
    {
        $status = array();
        for($i = 0; $i < count($_FILES[$file]["name"]); $i++) {
            $status[$i] = self::upload(method: "upload-anime-video", file: $file, filePos: $i, video_id: $videoId);
            if($status[$i]->isError()){
                return $status[$i];
            }
        }
        $ret = array();
        $original = array();
        foreach($status as $item){
            $ret[] = $item->getReturn()[0];
            $original = $item->getBareReturn()[0];
        }
        return new Status(isError: false, return: array($ret), bareReturn: array($original));

    }

    /**
     * @param String $method
     * @param String $file
     * @param int|null $filePos
     * @param String|null $title
     * @param String|null $description
     * @param String|null $extension
     * @param String|null $video_id
     * @return Status
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws ReflectionException
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public static function upload(String $method, String $file, ?int $filePos = null, ?String $title = null, ?String $description = null, ?String $extension = null, ?String $video_id = null) : Status
    {
        if ($method === "register-file") {
            $resource = new Resource();
            $resource->setPath($file);
            $resource->setTitle($title);
            $resource->setDescription($description);
            $resource->setExtension($extension);
            $resource->store();
            return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
        } else if ($method === "upload-anime-video"){
            $videos = Video::find(id: $video_id);
            if($videos->size() === 0){
                return new Status(isError: true, message: "Não foi possível encontrar o ID do vídeo.");
            }
            $_file = new File($_FILES[$file]["name"][$filePos], $_FILES[$file]["type"][$filePos], $_FILES[$file]["size"][$filePos], $_FILES[$file]["tmp_name"][$filePos], $_FILES[$file]["error"][$filePos], $_FILES[$file]["full_path"][$filePos]);
            $video = $videos[0];
            $tmp_name = $_file->getTmpName();
            $extension = explode(".", $_file->getName());
            $extension = $extension[count($extension)-1];
            $resource = new Resource();
            $resource->setTitle($video->getTitle());
            $resource->setDescription($video->getAnime()?->getTitle() . " - " . $video->getSynopsis());
            $resource->setPath("processing");
            $resource->setExtension($extension);
            $resource->store();
            $path = Utils::correctURL(Utils::getBasePath() . "\\" . self::ANIME_VIDEOS_DIRECTORY . "\\" . $resource->getId() . "." . $extension);
            move_uploaded_file($tmp_name, $path);
            $resource->setPath($path);
            $video->setPath($resource);
            $video->store($video->getAnime());
            return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
        } else if ($method === "upload-file"){
            $_file = new File($_FILES[$file]["name"][$filePos], $_FILES[$file]["type"][$filePos], $_FILES[$file]["size"][$filePos], $_FILES[$file]["tmp_name"][$filePos], $_FILES[$file]["error"][$filePos], $_FILES[$file]["full_path"][$filePos]);
            $resource = new Resource();
            $resource->setPath("processing");
            $resource->setTitle($title);
            $resource->setDescription($description);
            $extension = explode(".", $_file->getName());
            $resource->setExtension($extension[count($extension)-1]);
            $resource->store();
            $tmp_name = $_file->getTmpName();
            $path = Utils::getBasePath() . "/" . self::RESOURCES_DIRECTORY . "/" . $resource->getId() . "." . $resource->getExtension();
            move_uploaded_file($tmp_name, $path);
            $resource->setPath($path);
            $resource->store();
            return new Status(isError: false, return: array($resource->toArray()), bareReturn: array($resource));
        } else {
            return new Status(isError: true, message: "Não foi possível encontrar este método de upload.");
        }
    }

}