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
        "Maturity",
        "NightMode",
        "Removal",
        "Sex",
        "Verification",
    );
    /*
     * Name of Objects
     * */
    private const Objects = array(
        "GlobalSetting",
        "Resource",
        "Language",
        "User",
        "SourceType",
        "Audience",
        "Anime",
        "Season",
        "VideoType",
        "Video",
        "Subtitle",
        "Dubbing",
        "PunishmentType",
        "Punishment",
        "Gender",
        "AnimeStatus",
        "TicketStatus",
        "Ticket",
        "TicketMessage",
        "Role",
        "Permission",
        "AccountPlan",
        "AccountPurchase",
        "LogAction",
        "Log"
    );


    private const GENERATE_LOCATION = "\\resources\\js\\models.ts";

    private const REPLACE_TO = array(
        "Cassandra\Blob" => array("value" => "string", "isArray" => false),
        "array" => array("value" => "any", "isArray" => true),
        "int" => array("value" => "number", "isArray" => false),
        "DateTime" => array("value" => "Date", "isArray" => false),
        "double" => array("value" => "number", "isArray" => false),
        "float" => array("value" => "number", "isArray" => false),
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

        foreach(self::Objects as $object){
            $class = 'export class '. $object .' {';
            $class .= "\n";
            $reflection = (new ReflectionClass("Objects\\" . $object));
            $assignments = "    public constructor(obj?: any){ \n        const obj_: any = obj || {};\n";

            $assignments .= "        this.id = (obj_.id !== undefined) ? obj_.id : null;\n";

            $class .= "    id: number;";
            $count = 0;
            foreach($reflection->getProperties() as $property){
                $count++;
                $class .= "\n";
                $name = $property->getName();
                $dataType = str_replace("?", "", $property->getType());
                $dataType = str_replace("Objects\\", "", $dataType);
                $isEnumerator = str_starts_with($dataType, "Enumerators\\");
                $dataType = str_replace("Enumerators\\", "", $dataType);
                $isArray = str_ends_with($dataType, "sArray");
                $dataType = str_replace("sArray", "", $dataType);

                if($isEnumerator){
                    $dataType = "number";
                }

                if(isset(self::REPLACE_TO[$dataType])){
                    $isArray = self::REPLACE_TO[$dataType]["isArray"];
                    $dataType = str_replace($dataType, self::REPLACE_TO[$dataType]["value"], $dataType);
                }

                $assignments .= "        this." . $name . " = (obj_.".$name." !== undefined) ? obj_.". $name ." : ". ($isArray ? "{}" : "null") .";";
                if(count($reflection->getProperties()) < $count) $assignments .= "\n";
                $class .= "    " . $name . ": " . $dataType . ";";
            }
            $assignments .= "\n    }";
            $class .= "\n\n" . $assignments;
            $class .= "\n}\n";
            fwrite($file, $class);

            // CONSTANT

            $count = 0;
            $const = "export const ". $object ."Flags = {\n";
            foreach($reflection->getConstants() as $constant => $value){
                $count++;
                $const .= '    ' . $constant. ': '. $value;
                if(count($reflection->getConstants()) > $count) $const .= ",\n";
            }
            $const .= "\n};\n";
            fwrite($file, $const);
        }
    }

}