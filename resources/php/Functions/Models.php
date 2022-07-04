<?php

namespace Functions;

use ReflectionClass;
use ReflectionEnum;
use ReflectionException;

class Models
{

    /*
     *  Compiler Steps:
     *         - Open Terminal
     *         - Type: cd C:\xampp\htdocs\Cyrus\resources\js
     *         - Type: tsc models.ts
     * */

    /*
     * Name of Enumerators
     * */
    private const Enumerators = array(
        "Availability",
        "DayOfWeek",
        "AnimeStatus",
        "Maturity",
        "Removal",
        "Sex",
        "Verification",
    );
    /*
     * Name of Objects
     * */
    private const Objects = array(
        "Objects\\GlobalSetting",
        "Objects\\Resource",
        "Objects\\Language",
        "Objects\\User",
        "Objects\\SourceType",
        "Objects\\Audience",
        "Objects\\Anime",
        "Objects\\Season",
        "Objects\\VideoType",
        "Objects\\Video",
        "Objects\\Subtitle",
        "Objects\\Dubbing",
        "Objects\\PunishmentType",
        "Objects\\Punishment",
        "Objects\\Gender",
        "Objects\\TicketStatus",
        "Objects\\Ticket",
        "Objects\\TicketMessage",
        "Objects\\Role",
        "Objects\\Permission",
        "Objects\\AccountPlan",
        "Objects\\AccountPurchase",
        "Objects\\LogAction",
        "Objects\\Log",
        "Objects\\CommentAnime",
        "Objects\\CommentVideo",
        "APIObjects\\WebFile",
    );


    private const GENERATE_LOCATION = "\\resources\\js\\models.ts";

    public const REPLACE_TO = array(
        "Cassandra\Blob" => array("value" => "string", "isArray" => false),
        "array" => array("value" => "any", "isArray" => true),
        "int" => array("value" => "number", "isArray" => false),
        "DateTime" => array("value" => "Date", "isArray" => false),
        "double" => array("value" => "number", "isArray" => false),
        "float" => array("value" => "number", "isArray" => false),
        "bool" => array("value" => "boolean", "isArray" => false),
    );

    /**
     * @throws ReflectionException
     */
    public static function generateModels() : void{

        $file = fopen(Utils::getBasePath() . self::GENERATE_LOCATION, "w");

        foreach(self::Enumerators as $enum){
            $enumerator = 'export const '. $enum .' = {';
            $reflection = (new ReflectionEnum("Enumerators\\" . $enum));
            $count = 0;
            foreach($reflection->getCases() as $case){
                $count++;
                $enumerator .= "\n";
                $name = $case->getName();
                $value = $case->getBackingValue();
                $value = is_string($value) ? '"'.$value .'"' : $value;
                $enumerator .= '    ' . $name . ': '. $value;
                if(count($reflection->getCases()) > $count) $enumerator .= ",";
            }
            $enumerator .= "\n};\n";
            fwrite($file, $enumerator);
        }
        $models = "export const models : any = { \n";
        $consts = "export const flags : any = { \n";
        $countObj = 0;
        foreach(self::Objects as $object){
            $countObj++;
            $objectName = str_replace("Objects\\", "", $object);
            $class = 'export class '. $objectName .' {';
            $class .= "\n";
            $reflection = (new ReflectionClass($object));
            $assignments = "    public constructor(obj?: any){ \n        const obj_: any = obj || {};\n";

            $assignments .= "        this.id = (obj_.id !== undefined) ? obj_.id : null;\n";

            $class .= "    id: number;";
            $count = 0;
            foreach($reflection->getProperties() as $property){
                $count++;
                $class .= "\n";
                $name = $property->getName();
                $dataType = str_replace("?", "", $property->getType());
                $isEntity = str_contains($dataType, "Objects");
                $isArray = str_ends_with($dataType, "sArray");
                $dataType = str_replace("sArray", "", $dataType);
                $dataType = str_replace("Objects\\", "", $dataType);
                $isEnumerator = str_starts_with($dataType, "Enumerators\\");
                $dataType = str_replace("Enumerators\\", "", $dataType);

                if($isEnumerator){
                    $dataType = "number";
                }

                if(isset(self::REPLACE_TO[$dataType])){
                    $isArray = self::REPLACE_TO[$dataType]["isArray"];
                    $dataType = str_replace($dataType, self::REPLACE_TO[$dataType]["value"], $dataType);
                }
                if($isEntity && !$isArray) {
                    $assignments .= "        this." . $name . " = (obj_." . $name . " !== undefined) ? (obj_.". $name ." !== null ? new ". $dataType . "(obj_." . $name . "): null) : " . ($isArray ? "[]" : "null") . ";";
                } else {
                    $assignments .= "        this." . $name . " = (obj_." . $name . " !== undefined) ? obj_." . $name . " : " . ($isArray ? "[]" : "null") . ";";
                }
                if(count($reflection->getProperties()) > $count) $assignments .= "\n";
                $class .= "    " . $name . ": " . $dataType . ($isArray ? "[]" : "") . ($isEntity && !$isArray ? " | null" : "") .";";
            }
            $assignments .= "\n    }";
            $class .= "\n\n" . $assignments;
            $class .= "\n}\n";
            //$class .= "export const ". $object ."Teste = {" . $object . ": " . $object ."}\n";
            $models .= '    "'.$objectName . '" : ' . $objectName;
            if(count(self::Objects) > $countObj) $models .= ",";
            $models .= "\n";
            fwrite($file, $class);

            // CONSTANT
            if(count($reflection->getConstants()) > 0) {
                $count = 0;
                $const = "export const " . $objectName . "Flags = {\n";
                foreach ($reflection->getConstants() as $constant => $value) {
                    $count++;
                    $const .= '    ' . $constant . ': {name: "' . $constant . '", value: ' . $value . "}";
                    if (count($reflection->getConstants()) > $count) $const .= ",\n";
                }
                $const .= "\n};\n";
                $consts .= '    "'.$objectName . 'Flags" : ' . $objectName . "Flags,";
                $consts .= "\n";
                fwrite($file, $const);
            }
        }
        $models .= "}";
        $consts = rtrim($consts, ",\n");
        $consts .= "\n}\n";
        fwrite($file, $consts);
        fwrite($file, $models);
    }

}