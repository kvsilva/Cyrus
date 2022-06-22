<?php

namespace Services;

use APIObjects\Response;
use APIObjects\Status;
use Constants\API_MESSAGES;
use JetBrains\PhpStorm\Pure;
use Objects\Anime;
use Objects\Entity;
use Objects\EntityArray;
use Objects\GlobalSetting;
use Objects\Resource;
use Objects\User;
use Objects\Video;
use ReflectionException;

class Search
{
    /**
     * @throws ReflectionException
     */
    public static function search(?String $title, $anime = true, $video = true, $flags = array(Entity::NORMAL)) : Status{
        if($title === null || strlen(trim($title)) === 0) return new Status(isError: false);
        $entities = new EntityArray(entity: null);

        $animeFlags = Entity::getFlagsByName(new Anime(), $flags);
        $videoFlags = Entity::getFlagsByName(new Video(), $flags);

        $setting = array(
            "video_default_thumbnail" => GlobalSetting::find(name: "video_default_thumbnail"),
            "anime_default_cape" => GlobalSetting::find(name: "anime_default_cape"),
            "anime_default_profile" => GlobalSetting::find(name: "anime_default_profile"),
        );
        $defaultThumbnail = $setting["video_default_thumbnail"]->size()>0 ? Resource::find(id: $setting["video_default_thumbnail"][0]->getValue()) : array();
        $defaultCape = $setting["anime_default_cape"]->size()>0 ? Resource::find(id: $setting["anime_default_cape"][0]->getValue()) : array();
        $defaultProfile = $setting["anime_default_profile"]->size()>0 ? Resource::find(id: $setting["anime_default_profile"][0]->getValue()) : array();


        if($anime) $entities->addAll(Anime::find(title: "%" . $title . "%", operator: "like", flags: $animeFlags));
        if($video) $entities->addAll(Video::find(title: "%" . $title . "%", operator: "like", flags: $videoFlags));
        $entities->sort(fn($a, $b) => strcmp($a->getTitle(), $b->getTitle()));
        $ret = array();
        foreach($entities as $entity){
            if(get_class($entity) == "Objects\Anime"){
                if($entity->getCape() === null && sizeof($defaultCape) > 0){
                    $entity->setCape($defaultCape[0]);
                }
                if($entity->getProfile() === null && count($defaultProfile) > 0){
                    $entity->setProfile($defaultProfile[0]);
                }
            } else if (get_class($entity) == "Objects\Video"){
                if($entity->getThumbnail() === null && count($defaultThumbnail) > 0){
                    $entity->setThumbnail($defaultThumbnail[0]);
                }
            }
            $ret[] = $entity->toArray();
        }
        return new Status(isError: false, return: $ret, bareReturn: $entities);

    }

}