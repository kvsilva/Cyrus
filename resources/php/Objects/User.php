<?php

/*
 * Class imports
 */

use MongoDB\BSON\Timestamp;

/*
 * Object Imports
 */
require_once (dirname(__FILE__).'/Role.php');
require_once (dirname(__FILE__).'/Resource.php');
require_once (dirname(__FILE__).'/Language.php');

/*
 * Exception Imports
 */
require_once (dirname(__FILE__).'/../Exceptions/ArgumentNotFound.php');
require_once (dirname(__FILE__).'/../Exceptions/InvalidDataType.php');
require_once (dirname(__FILE__).'/../Exceptions/NotNullable.php');

/*
 * Enumerator Imports
 */
require_once (dirname(__FILE__).'/../Enumerators/Availability.php');
require_once (dirname(__FILE__).'/../Enumerators/NightMode.php');
require_once (dirname(__FILE__).'/../Enumerators/Verification.php');

/*
 * Others
 */
require_once (dirname(__FILE__).'/../database.php');


class User {

    // Flags

    public const NORMAL = 0;
    public const ALL = 0;
    public const ROLES = 0;
    public const PUNISHMENTS = 0;

    // DEFAULT STRUCTURE

    private int $id;
    private String $email;
    private String $username;
    private String $password;
    private DateTime $birthdate;
    private String $sex;
    private Timestamp $creation_date;
    private String $status;
    private Resource $profile_image;
    private Resource $profile_background;
    private String $about_me;
    private Verification $verified;
    private Language $display_language;
    private Language $email_communication_language;
    private Language $translation_language;
    private NightMode $night_mode;
    private Availability $available;

    // RELATIONS

    private array $flags;

    // User::Roles
    private array $roles = array();

    // User::Punishments
    private array $punishments = array();


    /**
     * @param int|null $id
     * @param array $flags
     * @throws ArgumentNotFound
     */
    function __construct(int $id = null, array $flags = array(User::NORMAL)) {
        $this->flags = $flags;
        if($id != null){
            GLOBAL $database;
            $query = $database->query("SELECT * FROM USER WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->email = $row["email"];
                $this->username = $row["username"];
                $this->password = $row["password"];
                $this->birthdate = $row["birthdate"];
                $this->sex = $row["sex"];
                $this->creation_date = $row["creation_date"];
                $this->status = $row["status"];
                $this->profile_image = new Resource($row["profile_image"]);
                $this->profile_background = new Resource($row["profile_background"]);
                $this->about_me = $row["about_me"];
                $this->verified = Verification::getVerification($row["verified"]);
                $this->display_language = new Language($row["display_language"]);
                $this->email_communication_language = new Language($row["email_communication_language"]);
                $this->translation_language = new Language($row["translation_language"]);
                $this->night_mode = NightMode::getNightMode($row["night_mode"]);
                $this->available = Availability::getAvailability($row["available"]);
                if(in_array(User::ROLES, $this->flags) || in_array(User::ALL, $this->flags)){
                    $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $id;");
                    while($row = $query->fetch_array()){
                        $this->roles[] = new Role($row["id"]);
                    }
                }
                if(in_array(User::PUNISHMENTS, $this->flags) || in_array(User::ALL, $this->flags)){
                    $query = $database->query("SELECT id FROM PUNISHMENT WHERE user = $id;");
                    while($row = $query->fetch_array()){
                        $this->punishments[] = new Punishment($row["id"]);
                    }
                }
            } else {
                throw new ArgumentNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     */
    public function store() : User{
        GLOBAL $database;
        $sql = "UPDATE USER SET /*id = '$this->id'*/, email = '$this->email', username = '$this->username', password = '$this->password', birthdate = '$this->birthdate', sex = '$this->sex', creation_date = '$this->creation_date', status = '$this->status', profile_image = '$this->profile_image->getId()', profile_background = '$this->profile_background->getId()', about_me = '$this->about_me, verified = '$this->verified, display_language = '$this->display_language->getId()', email_communication_language = '$this->email_communication_language->getId()', translation_language = '$this->translation_language->getId()', night_mode = '$this->night_mode'";
        $database->query($sql);
        if(in_array(User::ROLES, $this->flags)){
            $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $this->id;");
            while($row = $query->fetch_array()){
                $remove = true;
                foreach ($role as $this->roles){
                    $role->store();
                    if($role->getId() == $row["id"]){
                        $remove = false;
                        break;
                    }
                    // Associar os roles com os users
                }
                if($remove) $database->query("DELETE FROM USER_ROLE WHERE user = $this->id AND role = $row[id]");
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database, however, for logging reasons, the record will only be hidden in queries.
     * After updating the property, it will call the store() function to update.
     * @return $this
     */
    public function remove() : User{
        $this->available = Availability::NOT_AVAILABLE;
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
     * @throws ArgumentNotFound
     */
    public static function find(int $id = null, string $email = null, string $username = null, Availability $availability = Availability::AVAILABLE, string $sql = null, array $flags = [USER::NORMAL]) : array{
        GLOBAL $database;
        $sql_command = "";
        if(isset($sql)){
            $sql_command = "SELECT id from USER WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from USER WHERE ";
            $sql_command .+ ($id != null ? "(id != null AND id = '$id') " : "");
            $sql_command .+ $email != null ? "(email != null AND email = '$email') " : "";
            $sql_command .+ $username != null ? "(username != null AND username = '$username') " : "";
            $sql_command .+ $availability != null ? "(available != null AND available = '$availability') " : "";
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new User($row["id"], $flags);
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail(): mixed
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail(mixed $email): User
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
    public function setPassword(String $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return DateTime|mixed
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $birthdate
     * @return User
     */
    public function setBirthdate(DateTime $birthdate): User
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * @return String
     */
    public function getSex(): String
    {
        return $this->sex;
    }

    /**
     * @param String $sex
     * @return User
     */
    public function setSex(String $sex): User
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
     * @param Timestamp $creation_date
     * @return User
     */
    public function setCreationDate(Timestamp $creation_date): User
    {
        $this->creation_date = $creation_date;
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
    public function getProfileImage()
    {
        return $this->profile_image;
    }

    /**
     * @param Resource $profile_image
     * @return User
     */
    public function setProfileImage(Resource $profile_image)
    {
        $this->profile_image = $profile_image;
        return $this;
    }

    /**
     * @return Resource
     */
    public function getProfileBackground()
    {
        return $this->profile_background;
    }

    /**
     * @param Resource $profile_background
     * @return User
     */
    public function setProfileBackground(Resource $profile_background)
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
     */
    public function removeRole(Role $role = null, int $id = null): User
    {
        if(isset($role)){
            if(is_a($role, 'Role')) {
                for ($i = 0; $i < count($this->roles); $i++) {
                    if ($this->roles[$id]->getId() == $role->getId()) {
                        unset($this->roles[$id]);
                    }
                }
            }
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