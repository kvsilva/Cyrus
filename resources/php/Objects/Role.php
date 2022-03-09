<?php
namespace Objects;
/*
 * Class imports
 */

use JetBrains\PhpStorm\Pure;
use MongoDB\BSON\Timestamp;

/*
 * Object Imports
 */
require_once (dirname(__FILE__).'/Permission.php');

/*
 * Exception Imports
 */
require_once(dirname(__FILE__) . '/../Exceptions/RecordNotFound.php');
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


class Role {

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;
    public const PERMISSIONS = 3;

    // DEFAULT STRUCTURE

    private int $id;
    private String $name;


    // RELATIONS

    private array $flags;

    // Role::Permissions
    private array $permissions = array();


    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     */
    function __construct(int $id = null, array $flags = array(Role::NORMAL)) {
        $this->flags = $flags;
        if($id != null){
            GLOBAL $database;
            $query = $database->query("SELECT * FROM ROLE WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->name = $row["name"];
                if(in_array(Role::PERMISSIONS, $this->flags) || in_array(Role::ALL, $this->flags)){
                    $query = $database->query("SELECT role as 'id' FROM ROLE_PERMISSION WHERE role = $id;");
                    while($row = $query->fetch_array()){
                        $this->permissions[] = new Permission($row["id"]);
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
     */
    public function store() : User{
        GLOBAL $database;
        $sql = "UPDATE ROLE SET name = '$this->name';";
        $database->query($sql);
        if(in_array(Role::PERMISSIONS, $this->flags)){
            $query = $database->query("SELECT permission as 'id' FROM role_permission WHERE role = $this->id;");
            while($row = $query->fetch_array()){
                $remove = true;
                foreach ($permission as $this->permissions){
                    $permission->store();
                    if($permission->getId() == $row["id"]){
                        $remove = false;
                        break;
                    }
                }
                if($remove) $database->query("DELETE FROM role_permission WHERE role = $this->id AND permission = $row[id]");
            }
            foreach ($permission as $this->permissions){
                $database->query("INSERT IGNORE INTO ROLE_PERMISSION (role, permission) VALUES ($this->id, $permission->getId())");
            }
        }
        return $this;
    }

    /**
     * This method will remove the object from the database, however, for logging reasons, the record will only be hidden in queries.
     * After updating the property, it will call the store() function to update.
     * @return $this
     */
    public function remove() : Role{
        GLOBAL $database;
        $database->query("DELETE FROM USER_ROLE where role = $this->id");
        $database->query("DELETE FROM role where id = $this->id");
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
     */
    public static function find(int $id = null, string $email = null, string $username = null, Availability $availability = Availability::AVAILABLE, string $sql = null, array $flags = [USER::NORMAL]) : array{
        GLOBAL $database;
        $sql_command = "";
        if($sql != null){
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

    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "email" => $this->email,
            "username" => $this->username,
            "birthdate" => $this->birthdate,
            "sex" => $this->sex,
            "creation_date" => $this->creation_date,
            "status" => $this->status,
            "profile_image" => $this->profile_image,
            "profile_background" => $this->profile_background,
            "about_me" => $this->about_me,
            "verified" => $this->verified->toArray(),
            "display_language" => $this->display_language,
            "email_communication_language" => $this->email_communication_language,
            "translation_language" => $this->translation_language,
            "night_mode" => $this->night_mode->toArray(),
            "available" => $this->available->toArray(),

            // Relations

            "roles" => count($this->roles) == 0 ? null : $this->roles,
            "punishments" => count($this->punishments) == 0 ? null : $this->punishments
        );
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