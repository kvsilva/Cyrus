<?php

namespace Objects;

use DateTime;
use Enumerators\Availability;
use Enumerators\NightMode;
use Enumerators\Removal;
use Enumerators\Sex;
use Enumerators\Verification;
use Exception;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidDataType;
use Exceptions\InvalidSize;
use Exceptions\IOException;
use Exceptions\MalformedJSON;
use Exceptions\NotNullable;
use Exceptions\RecordNotFound;
use Exceptions\TableNotFound;
use Exceptions\UniqueKey;
use Functions\Database;
use ReflectionException;

class User extends Entity
{
    // FLAGS

    public const ROLES = 2;
    public const PUNISHMENTS = 3;
    public const LOGS = 4;
    public const PURCHASES = 5;
    public const TICKETS = 6;

    // DEFAULT STRUCTURE

    protected ?String $email = null;
    protected ?String $username = null;
    protected ?String $password = null;
    protected ?DateTime $birthdate = null;
    protected ?Sex $sex = null;
    protected ?DateTime $creation_date = null;
    protected ?String $status = null;
    protected ?Resource $profile_image = null;
    protected ?Resource $profile_background = null;
    protected ?String $about_me = null;
    protected ?Verification $verified = null;
    protected ?Language $display_language = null;
    protected ?Language $email_communication_language = null;
    protected ?Language $translation_language = null;
    protected ?NightMode $night_mode = null;
    protected ?Availability $available = null;

    // RELATIONS

    // User::Roles
    private ?array $roles = null;

    // User::Punishments
    private ?array $punishments = null;

    // User::Logs
    private ?array $logs = null;

    // User::Purchases
    private ?array $purchases = null;

    // User::Tickets
    private ?array $tickets = null;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws ReflectionException
     * @throws RecordNotFound
     */
    public function __construct(int $id = null, array $flags = array(self::NORMAL))
    {
        parent::__construct(table: "user", id: $id, flags: $flags);
    }

    /**
     * @return void
     * @throws RecordNotFound
     * @throws ReflectionException
     */
    protected function buildRelations()
    {
        $id = $this->getId();
        $database = $this->getDatabase();
        if($this->hasFlag(self::ROLES)){
            $this->roles = array();
            $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->roles[] = new Role($row["id"]);
            }
        }
        if($this->hasFlag(self::PUNISHMENTS)){
            $this->punishments = array();
            $query = $database->query("SELECT id FROM PUNISHMENT WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->punishments[] = new Punishment($row["id"]);
            }
        }
        if($this->hasFlag(self::LOGS)){
            $this->logs = array();
            $query = $database->query("SELECT id FROM log WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->logs[] = new Log($row["id"]);
            }
        }
        if($this->hasFlag(self::PURCHASES)){
            $this->logs = array();
            $query = $database->query("SELECT id FROM account_purchase WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->logs[] = new AccountPurchase($row["id"]);
            }
        }
        if($this->hasFlag(self::TICKETS)){
            $this->tickets = array();
            $query = $database->query("SELECT id FROM ticket WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->tickets[] = new Ticket($row["id"]);
            }
        }
    }

    /**
     * @return $this
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store() : User{
        parent::__store();
        return $this;
    }

    /**
     * @return void
     */
    protected function updateRelations()
    {
        $database = $this->getDatabase();
        $id = $this->getId();
        if ($this->hasFlag(self::ROLES)) {
            $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->roles as $role) {
                    $role->store();
                    if ($role->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) $database->query("DELETE FROM USER_ROLE WHERE user = $id AND role = $row[id]");
            }
            foreach ($this->roles as $role) {
                $database->query("INSERT IGNORE INTO USER_ROLE (user, role) VALUES ($id, $role->getId())");
            }
        }
        if ($this->hasFlag(self::PUNISHMENTS)) {
            $query = $database->query("SELECT id FROM punishment WHERE user = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->punishments as $punishment) {
                    if ($punishment->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    try { (new Punishment($row["id"]))->remove(); } catch (MalformedJSON|RecordNotFound|IOException|Exception $e) {}
                }
            }
            foreach ($this->punishments as $punishment) {
                $punishment->store($this);
            }
        }
        if ($this->hasFlag(self::LOGS)) {
            $query = $database->query("SELECT id FROM log WHERE user = $id");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->logs as $log) {
                    if ($log->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    try { (new Log($row["id"]))->remove(); } catch (MalformedJSON|RecordNotFound|IOException|Exception $e) {}
                }
            }
            foreach ($this->logs as $log) {
                $log->store();
            }
        }
        if ($this->hasFlag(self::PURCHASES)) {
            $query = $database->query("SELECT id FROM account_purchase WHERE user = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->purchases as $purchase) {
                    if ($purchase->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    try { (new AccountPurchase($row["id"]))->remove(); } catch (MalformedJSON|RecordNotFound|IOException|Exception $e) {}
                }
            }
            foreach ($this->purchases as $purchase) {
                $purchase->store($this);
            }
        }
        if ($this->hasFlag(self::TICKETS)) {
            $query = $database->query("SELECT id FROM ticket WHERE user = $id;");
            while ($row = $query->fetch_array()) {
                $remove = true;
                foreach ($this->tickets as $ticket) {
                    if ($ticket->getId() == $row["id"]) {
                        $remove = false;
                        break;
                    }
                }
                if ($remove) {
                    try { (new Ticket($row["id"]))->remove(); } catch (RecordNotFound|IOException|Exception $e) {}
                }
            }
            foreach ($this->tickets as $ticket) {
                $ticket->store($this);
            }
        }
    }

    /**
     * @throws IOException
     */
    public function remove() : User{
        parent::__remove(method: Removal::AVAILABILITY);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $email = null, string $username = null, Availability $available = Availability::AVAILABLE,  string $sql = null, array $flags = [self::NORMAL]) : array{
        return parent::__find(fields: array(
            "id" => $id,
            "email" => $email,
            "username" => $username,
            "available" => $available?->value
        ), table: 'user', class: 'Objects\User', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     */
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("user"),
            "email" => $this->email,
            "username" => $this->username,
            "password" => $this->password,
            "birthdate" => $this->birthdate?->format(Database::DateFormat),
            "sex" => $this->sex != null ? $this->sex->value : Sex::OTHER->value,
            "creation_date" => $this->creation_date?->format(Database::DateFormat),
            "status" => $this->status,
            "profile_image" => $this->profile_image?->store()->getId(),
            "profile_background" => $this->profile_background?->store()->getId(),
            "about_me" => $this->about_me,
            "verified" => $this->verified != null ? $this->verified->value : Verification::VERIFIED->value,
            "display_language" => $this->display_language?->store()->getId(),
            "email_communication_language" => $this->email_communication_language?->store()->getId(),
            "translation_language" => $this->translation_language?->store()->getId(),
            "night_mode" => $this->night_mode != null ? $this->night_mode->value : NightMode::DISABLE->value,
            "available" => $this->available != null ? $this->available->value : Availability::AVAILABLE->value
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = array(
            "id" => $this->getId(),
            "email" => $this->email,
            "username" => $this->username,
            "birthdate" => $this->birthdate?->format(Database::DateFormat),
            "sex" => $this->sex,
            "creation_date" => $this->creation_date?->format(Database::DateFormat),
            "status" => $this->status,
            "profile_image" => $this->profile_image?->toArray(),
            "profile_background" =>$this->profile_background?->toArray(),
            "about_me" => $this->about_me,
            "verified" => $this->verified?->toArray(),
            "display_language" => $this->display_language?->toArray(),
            "email_communication_language" => $this->email_communication_language?->toArray(),
            "translation_language" => $this->translation_language?->toArray(),
            "night_mode" => $this->night_mode?->toArray(),
            "available" => $this->available?->toArray()
        );
        // Relations
        $array["roles"] = $this->roles != null ? array() : null;
        if($array["roles"] != null) foreach($this->roles as $value) $array["roles"][] = $value->toArray();
        $array["logs"] = $this->logs != null ? array() : null;
        if($array["logs"] != null) foreach($this->logs as $value) $array["logs"][] = $value->toArray();
        $array["punishments"] = $this->punishments != null ? array() : null;
        if($array["punishments"] != null) foreach($this->punishments as $value) $array["punishments"][] = $value->toArray();
        $array["purchases"] = $this->purchases != null ? array() : null;
        if($array["purchases"] != null) {
            $array["current"][] = null;
            $array["rescue"][] = array();
            $array["rescued"][] = array();
            $array["revoked"][] = array();
            foreach($this->purchases as $value) {
                if($value->isActive()){
                    $array["purchases"]["current"] = $value->toArray();
                } else if ($value->isRedeemable()){
                    $array["purchases"]["rescue"][] = $value->toArray();
                } else if ($value->isRescued()){
                    $array["purchases"]["rescued"][] = $value->toArray();
                } else if ($value->isRevoked()){
                    $array["purchases"]["revoked"][] = $value->toArray();
                }
            }
        }
        $array["tickets"] = $this->tickets != null ? array() : null;
        if($array["tickets"] != null) foreach($this->tickets as $value) $array["tickets"][] = $value->toArray();
        return $array;
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
     * @return DateTime
     */
    public function getCreationDate(): DateTime
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
        $this->roles[] = $role;
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
        $remove = array();
        if($role != null){
            for ($i = 0; $i < count($this->roles); $i++) {
                if ($this->roles[$i]->getId() == $role->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->roles); $i++) {
                if ($this->roles[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->roles[$item]);
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

    /**
     * @param Punishment $punishment
     * @return User
     */
    public function addPunishment(Punishment $entity): User
    {
        $this->punishments[] = $entity;
        return $this;
    }

    /**
     * @param Punishment|null $entity
     * @param int|null $id
     * @return $this
     */
    public function removePunishment(Punishment $entity = null, int $id = null): User
    {
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->punishments); $i++) {
                if ($this->punishments[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->punishments); $i++) {
                if ($this->punishments[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->punishments[$item]);
        return $this;
    }

    /**
     * @return array|null
     */
    public function getLogs(): ?array
    {
        return $this->logs;
    }

    /**
     * @param array|null $logs
     * @return User
     */
    public function setLogs(?array $logs): User
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     * @param Log $entity
     * @return User
     */
    public function addLog(Log $entity): User
    {
        $this->logs[] = $entity;
        return $this;
    }

    /**
     * @param Log|null $entity
     * @param int|null $id
     * @return $this
     */
    public function removeLog(Log $entity = null, int $id = null): User
    {
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->logs); $i++) {
                if ($this->logs[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->logs); $i++) {
                if ($this->logs[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->logs[$item]);
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPurchases(): ?array
    {
        return $this->purchases;
    }

    /**
     * @param array|null $purchases
     * @return User
     */
    public function setPurchases(?array $purchases): User
    {
        $this->purchases = $purchases;
        return $this;
    }

    /**
     * @param AccountPurchase $entity
     * @return User
     */
    public function addPurchase(AccountPurchase $entity): User
    {
        $this->purchases[] = $entity;
        return $this;
    }

    /**
     * @param AccountPurchase|null $entity
     * @param int|null $id
     * @return $this
     */
    public function removePurchase(AccountPurchase $entity = null, int $id = null): User
    {
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->purchases); $i++) {
                if ($this->purchases[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->purchases); $i++) {
                if ($this->purchases[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->purchases[$item]);
        return $this;
    }

    /**
     * @return array|null
     */
    public function getTickets(): ?array
    {
        return $this->tickets;
    }

    /**
     * @param array|null $tickets
     * @return User
     */
    public function setTickets(?array $tickets): User
    {
        $this->tickets = $tickets;
        return $this;
    }

    /**
     * @param Ticket $entity
     * @return User
     */
    public function addTicket(Ticket $entity): User
    {
        $this->tickets[] = $entity;
        return $this;
    }

    /**
     * @param Ticket|null $entity
     * @param int|null $id
     * @return $this
     */
    public function removeTicket(Ticket $entity = null, int $id = null): User
    {
        $remove = array();
        if($entity != null){
            for ($i = 0; $i < count($this->tickets); $i++) {
                if ($this->tickets[$i]->getId() == $entity->getId()) {
                    $remove[] = $i;
                }
            }
        } else if($id != null) {
            for ($i = 0; $i < count($this->tickets); $i++) {
                if ($this->tickets[$i]->getId() == $id) {
                    $remove[] = $i;
                }
            }
        }
        foreach($remove as $item) unset($this->tickets[$item]);
        return $this;
    }

}