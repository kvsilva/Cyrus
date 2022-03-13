<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\TableNotFound;
use JetBrains\PhpStorm\Pure;
use MongoDB\BSON\Timestamp;
use mysqli;

/*
 * Object Imports
 */

use Objects\Role;
use Objects\Permission;
use Objects\Language;
use Objects\Resource;
use Objects\Log;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\NotNullable;
use Exceptions\InvalidDataType;
use Exceptions\RecordNotFound;
use Exceptions\MalformedJSON;
use Exceptions\IOException;

/*
 * Enumerator Imports
 */
use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Verification;
use Enumerators\Sex;

/*
 * Others
 */
use Functions\Database;


class User {

    // Database
    private ?Mysqli $database = null;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const ROLES = 2;
    public const PUNISHMENTS = 3;
    public const LOGS = 4;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $email = null;
    private ?String $username = null;
    private ?String $password = null;
    private ?DateTime $birthdate = null;
    private ?Sex $sex = null;
    private ?DateTime $creation_date = null;
    private ?String $status = null;
    private ?Resource $profile_image = null;
    private ?Resource $profile_background = null;
    private ?String $about_me = null;
    private ?Verification $verified = null;
    private ?Language $display_language = null;
    private ?Language $email_communication_language = null;
    private ?Language $translation_language = null;
    private ?NightMode $night_mode = null;
    private ?Availability $available = null;

    // RELATIONS

    private array $flags;

    // User::Roles
    private array $roles = array();

    // User::Punishments
    private array $punishments = array();

    // User::Logs
    private array $logs = array();


    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws MalformedJSON
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        $this->flags = $flags;
        if($id != null && $this->database != null){
            $database = $this->database;
            $query = $database->query("SELECT * FROM USER WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->email = $row["email"];
                $this->username = $row["username"];
                $this->password = $row["password"];
                $this->birthdate = $row["birthdate"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["birthdate"] . " 00:00:00") : null;
                $this->sex = $row["sex"] != "" ? Sex::getSex($row["sex"]) : null;
                $this->creation_date = $row["creation_date"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["creation_date"]) : null;
                $this->status = $row["status"];
                $this->profile_image = $row["profile_image"] != "" ? new Resource($row["profile_image"]) : null;
                $this->profile_background = $row["profile_background"] != "" ? new Resource($row["profile_background"]) : null;
                $this->about_me = $row["about_me"];

                $this->verified = Verification::getVerification($row["verified"]);
                $this->display_language = $row["display_language"] != "" ? new Language($row["display_language"]) : null;
                $this->email_communication_language = $row["email_communication_language"] != "" ? new Language($row["email_communication_language"]) : null;
                $this->translation_language = $row["translation_language"] != "" ? new Language($row["translation_language"]) : null;
                $this->night_mode = NightMode::getNightMode($row["night_mode"]);
                $this->available = Availability::getAvailability($row["available"]);
                if(in_array(self::ROLES, $this->flags) || in_array(self::ALL, $this->flags)){
                    $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $id;");
                    while($row = $query->fetch_array()){
                        $this->roles[] = new Role($row["id"]);
                    }
                }
                if(in_array(self::PUNISHMENTS, $this->flags) || in_array(self::ALL, $this->flags)){
                    $query = $database->query("SELECT id FROM PUNISHMENT WHERE user = $id;");
                    while($row = $query->fetch_array()){
                        $this->punishments[] = new Punishment($row["id"]);
                    }
                }
                if(in_array(self::LOGS, $this->flags) || in_array(self::ALL, $this->flags)){
                    $query = $database->query("SELECT id FROM log WHERE user = $id;");
                    while($row = $query->fetch_array()){
                        $this->logs[] = new Log($row["id"]);
                    }
                }
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     * @throws IOException
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws TableNotFound
     * @throws InvalidSize
     */
    public function store() : User
    {
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;

        $query_keys_values = array(
            "id" => $this->id,
            "email" => $this->email,
            "username" => $this->username,
            "password" => $this->password,
            "birthdate" => isset($this->birthdate) ? $this->birthdate->format(Database::DateFormat) : null,
            "sex" => isset($this->sex) ? $this->sex->value : Sex::OTHER->value,
            "creation_date" => isset($this->creation_date) ? $this->creation_date->format(Database::DateFormat) : null,
            "status" => $this->status,
            "profile_image" => isset($this->profile_image) ? $this->profile_image->store()->getId() : null,
            "profile_background" => isset($this->profile_background) ? $this->profile_background->store()->getId() : null,
            "about_me" => $this->about_me,
            "verified" => isset($this->verified) ? $this->verified->value : Verification::VERIFIED->value,
            "display_language" => isset($this->display_language) ? $this->display_language->store()->getId() : null,
            "email_communication_language" => isset($this->email_communication_language) ? $this->email_communication_language->store()->getId() : null,
            "translation_language" => isset($this->translation_language) ? $this->translation_language->store()->getId() : null,
            "night_mode" => isset($this->night_mode) ? $this->night_mode->value : NightMode::DISABLE->value,
            "available" => isset($this->available) ? $this->available->value : Availability::AVAILABLE->value
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "user")) {
                $size = Database::getColumnSize(column: $key, table: "user");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            }
        }
        if ($this->id == null || $database->query("SELECT id from user where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "user") && !Database::isUniqueValue(column: $key, table: "user", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("user");

            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach ($query_keys_values as $key => $value) {
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys, 0, -1);
            $sql_values = substr($sql_values, 0, -1);
            $sql = "INSERT INTO user ($sql_keys) VALUES ($sql_values)";

        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "user") && !Database::isUniqueValue(column: $key, table: "user", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach ($query_keys_values as $key => $value) {
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql, 0, -1);
            $sql = "UPDATE user SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        // Relations
        if (in_array(self::ROLES, $this->flags)) {
            $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $this->id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->roles as $role) {
                    $role->store();
                    if ($role->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) $database->query("DELETE FROM USER_ROLE WHERE user = $this->id AND role = $row[id]");
            }
            foreach ($this->roles as $role) {
                $database->query("INSERT IGNORE INTO USER_ROLE (user, role) VALUES ($this->id, $role->getId())");
            }
        }
        if (in_array(self::LOGS, $this->flags)) {
            $query = $database->query("SELECT id FROM log WHERE user = $this->id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->logs as $log) {
                    if ($log->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) $database->query("DELETE FROM log WHERE id = $row[id]");
            }
            foreach ($this->logs as $log) {
                $log->store();
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database, however, for logging reasons, the record will only be hidden in queries.
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function remove() : User{
        $this->available = Availability::NOT_AVAILABLE;
        $this->store();
        return $this;
    }

    /**
     * @param int|null $id
     * @param string|null $email
     * @param string|null $username
     * @param Availability $availability
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     * @throws MalformedJSON
     */
    public static function find(int $id = null, string $email = null, string $username = null, Availability $availability = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from USER WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from USER WHERE " .
                ($id != null ? "(id != null AND id = '$id') " : "") .
                ($email != null ? "(email != null AND email = '$email') " : "") .
                ($username != null ? "(username != null AND username = '$username') " : "") .
                ($availability != null ? "(available != null AND available = '$availability->value') " : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new User($row["id"], $flags);
        }
        return $result;
    }

    #[Pure]
    public function toArray(): array
    {
        $array = array(
            "id" => $this->id,
            "email" => $this->email,
            "username" => $this->username,
            "birthdate" => $this->birthdate,
            "sex" => $this->sex,
            "creation_date" => $this->creation_date,
            "status" => $this->status,
            "profile_image" => isset($this->profile_image) ? $this->profile_image->toArray() : null,
            "profile_background" => isset($this->profile_background) ? $this->profile_background->toArray() : null,
            "about_me" => $this->about_me,
            "verified" => isset($this->verified) ? $this->verified->toArray() : null,
            "display_language" => isset($this->display_language) ? $this->display_language->toArray() : null,
            "email_communication_language" => isset($this->email_communication_language) ? $this->email_communication_language->toArray() : null,
            "translation_language" => isset($this->translation_language) ? $this->translation_language->toArray() : null,
            "night_mode" => isset($this->night_mode) ? $this->night_mode->toArray() : null,
            "available" => isset($this->available) ? $this->available->toArray() : null
        );
        // Relations
        $array["roles"] = count($this->roles) == 0 ? null : array();
        foreach($this->roles as $value) $array["roles"][] = $value->toArray();
        $array["logs"] = count($this->roles) == 0 ? null : array();
        foreach($this->logs as $value) $array["logs"][] = $value->toArray();
        $array["punishments"] = count($this->roles) == 0 ? null : array();
        foreach($this->punishments as $value) $array["punishments"][] = $value->toArray();
        return $array;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return String
     */
    public function getEmail(): String
    {
        return $this->email;
    }

    /**
     * @param String $email
     * @return User
     */
    public function setEmail(String $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return String
     */
    public function getUsername(): String
    {
        return $this->username;
    }

    /**
     * @param String $username
     * @return User
     */
    public function setUsername(String $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return String
     */
    public function getPassword(): String
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword(String $password, bool $encrypt = true): User
    {
        $this->password = $encrypt ? password_hash($password, PASSWORD_DEFAULT) : $password;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime|String $birthdate
     * @return User
     */
    public function setBirthdate(DateTime|String $birthdate): User
    {
        if(is_string($birthdate)){
            $this->birthdate = DateTime::createFromFormat(Database::DateFormat, $birthdate);
        } else if(is_a($birthdate, "DateTime")){
            $this->birthdate = $birthdate;
        }
        return $this;
    }

    /**
     * @return Sex
     */
    public function getSex(): Sex
    {
        return $this->sex;
    }

    /**
     * @param Sex $sex
     * @return User
     */
    public function setSex(Sex $sex): User
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return Timestamp
     */
    public function getCreationDate(): String
    {
        return $this->creation_date;
    }

    /**
     * @param DateTime|String $creation_date
     * @return User
     */
    public function setCreationDate(DateTime|String $creation_date): User
    {
        if(is_string($creation_date)){
            $this->creation_date = DateTime::createFromFormat(Database::DateFormat, $creation_date);
        } else if(is_a($creation_date, "DateTime")){
            $this->creation_date = $creation_date;
        }
        return $this;
    }

    /**
     * @return String
     */
    public function getStatus(): String
    {
        return $this->status;
    }

    /**
     * @param String $status
     * @return User
     */
    public function setStatus(String $status): User
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Resource
     */
    public function getProfileImage() : Resource
    {
        return $this->profile_image;
    }

    /**
     * @param Resource $profile_image
     * @return User
     */
    public function setProfileImage(Resource $profile_image) : User
    {
        $this->profile_image = $profile_image;
        return $this;
    }

    /**
     * @return Resource
     */
    public function getProfileBackground() : Resource
    {
        return $this->profile_background;
    }

    /**
     * @param Resource $profile_background
     * @return User
     */
    public function setProfileBackground(Resource $profile_background) : User
    {
        $this->profile_background = $profile_background;
        return $this;
    }

    /**
     * @return String
     */
    public function getAboutMe(): String
    {
        return $this->about_me;
    }

    /**
     * @param String $about_me
     * @return User
     */
    public function setAboutMe(String $about_me): User
    {
        $this->about_me = $about_me;
        return $this;
    }

    /**
     * @return Verification|null
     */
    public function getVerified(): ?Verification
    {
        return $this->verified;
    }

    /**
     * @param Verification|null $verified
     * @return User
     * @throws NotNullable
     */
    public function setVerified(?Verification $verified): User
    {
        if($verified != null) {
            $this->verified = $verified;
        } else throw new NotNullable("verified");
        return $this;
    }

    /**
     * @return Language
     */
    public function getDisplayLanguage(): Language
    {
        return $this->display_language;
    }

    /**
     * @param Language $display_language
     * @return User
     */
    public function setDisplayLanguage(Language $display_language): User
    {
        $this->display_language = $display_language;
        return $this;
    }

    /**
     * @return Language
     */
    public function getEmailCommunicationLanguage(): Language
    {
        return $this->email_communication_language;
    }

    /**
     * @param Language $email_communication_language
     * @return User
     */
    public function setEmailCommunicationLanguage(Language $email_communication_language): User
    {
        $this->email_communication_language = $email_communication_language;
        return $this;
    }

    /**
     * @return Language
     */
    public function getTranslationLanguage(): Language
    {
        return $this->translation_language;
    }

    /**
     * @param Language $translation_language
     * @return User
     */
    public function setTranslationLanguage(Language $translation_language): User
    {
        $this->translation_language = $translation_language;
        return $this;
    }

    /**
     * @return NightMode|null
     */
    public function getNightMode(): ?NightMode
    {
        return $this->night_mode;
    }

    /**
     * @param NightMode|null $night_mode
     * @return User
     * @throws NotNullable
     */
    public function setNightMode(?NightMode $night_mode): User
    {
        if($night_mode != null){
            $this->night_mode = $night_mode;
        } else throw new NotNullable("night_mode");
        return $this;
    }

    /**
     * @return Availability|null
     */
    public function getAvailable(): ?Availability
    {
        return $this->available;
    }

    /**
     * @param Availability|null $available
     * @return User
     * @throws NotNullable
     */
    public function setAvailable(?Availability $available): User
    {
        if($available != null){
            $this->available = $available;
        } else throw new NotNullable("available");
        return $this;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param Role $role
     * @return User
     * @throws InvalidDataType
     */
    public function addRole(Role $role): User
    {
        if(is_a($role, 'Role')){
            $this->roles[] = $role;
        } else throw new InvalidDataType("role", "Role");
        return $this;
    }

    /**
     * @param Role|null $role
     * @param int|null $id
     * @return $this
     * @throws InvalidDataType
     */
    public function removeRole(Role $role = null, int $id = null): User
    {
        if(isset($role)){
            if(is_a($role, 'Role')) {
                for ($i = 0; $i < count($this->roles); $i++) {
                    if ($this->roles[$i]->getId() == $role->getId()) {
                        unset($this->roles[$i]);
                    }
                }
            } else throw new InvalidDataType("permission", "Permission");
        } else if (isset($id)){
            for ($i = 0; $i < count($this->roles); $i++) {
                if ($this->roles[$id]->getId() == $id) {
                    unset($this->roles[$id]);
                }
            }
        }
        return $this;
    }

    /**
     * @param Permission $permission
     * @param String|null $tag
     * @return bool
     */
    public function hasPermission(Permission $permission, String $tag = null) : bool{
        foreach($this->roles as $element){
            if($element->hasPermission($permission, $tag)) return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getPunishments(): array
    {
        return $this->punishments;
    }

    /**
     * @param array $punishments
     * @return User
     */
    public function setPunishments(array $punishments): User
    {
        $this->punishments = $punishments;
        return $this;
    }


}



?>