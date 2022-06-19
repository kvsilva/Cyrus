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
use Exceptions\NotInitialized;
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
    public const ANIME_STATUS = 7;

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

    // User::RolesArray
    private ?RolesArray $roles = null;

    // User::Punishments
    private ?PunishmentsArray $punishments = null;

    // User::Logs
    private ?LogsArray $logs = null;

    // User::Purchases
    private ?AccountPurchasesArray $purchases = null;

    // User::Tickets
    private ?TicketsArray $tickets = null;

    // User::AnimeStatus
    /**
     * @var array|null
     * array(
     *      array(
     *          "status" => (new AnimeStatus()),
     *          "list" => array(
     *          )
     *       ),
     *      array(
     *          "status" => (new AnimeStatus()),
     *          "list" => array(
     *          )
     *       )
     *   );
     */
    private ?array $anime_status = null;


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
            $this->roles = new RolesArray();
            $query = $database->query("SELECT role as 'id' FROM USER_ROLE WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->roles[] = new Role($row["id"]);
            }
        }
        if($this->hasFlag(self::PUNISHMENTS)){
            $this->punishments = new PunishmentsArray();
            $query = $database->query("SELECT id FROM PUNISHMENT WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->punishments[] = new Punishment($row["id"]);
            }
        }
        if($this->hasFlag(self::LOGS)){
            $this->logs = new LogsArray();
            $query = $database->query("SELECT id FROM log WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->logs[] = new Log($row["id"]);
            }
        }
        if($this->hasFlag(self::PURCHASES)){
            $this->purchases = new AccountPurchasesArray();
            $query = $database->query("SELECT id FROM account_purchase WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->purchases[] = new AccountPurchase($row["id"]);
            }
        }
        if($this->hasFlag(self::TICKETS)){
            $this->tickets = new TicketsArray();
            $query = $database->query("SELECT id FROM ticket WHERE user = $id;");
            while($row = $query->fetch_array()){
                $this->tickets[] = new Ticket($row["id"]);
            }
        }
        if($this->hasFlag(self::ANIME_STATUS)){
            $this->anime_status = array();
            $query = $database->query("SELECT DISTINCT status as 'id' FROM user_anime_status WHERE user = $id;");
            while($row = $query->fetch_array()){
                $status = new AnimeStatus($row["id"]);
                $this->anime_status[] = array("status" => $status, "list" => array());
                $animes = $database->query("SELECT anime as 'id' FROM user_anime_status WHERE user = $id and status = $row[id] ORDER BY date;");
                while($anime = $animes->fetch_array()){
                    $this->anime_status[sizeof($this->anime_status)]["list"] = new Anime($anime["id"]);
                }
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
     * @throws ReflectionException|RecordNotFound
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
                $role_id = $role->getId();
                $database->query("INSERT IGNORE INTO USER_ROLE (user, role) VALUES ($id, $role_id)");
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
                    try { (new Punishment($row["id"]))->remove(); } catch (RecordNotFound|IOException|Exception $e) {}
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
                    try { (new Log($row["id"]))->remove(); } catch (RecordNotFound|IOException|Exception $e) {}
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
                    try { (new AccountPurchase($row["id"]))->remove(); } catch (RecordNotFound|IOException|Exception $e) {}
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
        if ($this->hasFlag(self::ANIME_STATUS)) {
            $query = $database->query("SELECT DISTINCT status as 'id' FROM user_anime_status WHERE user = $id;");
            while ($row = $query->fetch_array()) {
                $status = new AnimeStatus($row["id"]);
                $status_list_loc = null;
                foreach($this->anime_status as $k => $v){
                    if($v["status"]->getId() == $status->getId()){
                        $status_list_loc = $k;
                        break;
                    }
                }
                $animes = $database->query("SELECT anime as 'id' FROM user_anime_status WHERE user = $id and status = $row[id] ORDER BY date;");
                while ($anime = $animes->fetch_array()) {
                    $remove = true;
                    foreach ($this->anime_status[$status_list_loc]["list"] as $anime_status) {
                        if ($anime_status->getId() == $anime["id"]) {
                            $remove = false;
                            break;
                        }
                    }
                    if ($remove) {
                        $database->query("DELETE FROM user_anime_status WHERE user = $id AND anime = $anime[id] AND status = $row[id]");
                    }
                }
            }
            foreach ($this->anime_status as $status_arr) {
                foreach ($status_arr["list"] as $key => $anime){
                    $anime->store();
                    $status = AnimeStatus::find(name: $key)[0];
                    $database->query("INSERT IGNORE INTO user_anime_status (user, anime, status) VALUES ($id, ". $anime->getId() .", ". $status->getId() .")");
                }
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
     * @param int|null $id
     * @param string|null $email
     * @param string|null $username
     * @param Availability $available
     * @param string|null $sql
     * @param array $flags
     * @return EntityArray|UsersArray
     * @throws ReflectionException
     */
    public static function find(int $id = null, string $email = null, string $username = null, Availability $available = Availability::AVAILABLE,  string $sql = null, array $flags = [self::NORMAL]): EntityArray|UsersArray
    {
        return parent::__find(fields: array(
            "id" => $id,
            "email" => $email,
            "username" => $username,
            "available" => $available?->value
        ), table: 'user', class: 'Objects\User', sql: $sql, flags: $flags);
    }

    /**
     * @return array
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    protected function valuesArray(): array
    {
        return array(
            "id" => $this->getId() != null ? $this->getId() : Database::getNextIncrement("user"),
            "email" => $this->email,
            "username" => $this->username,
            "password" => $this->password != null && password_get_info($this->password)["algoName"] === 'unknown' ? $this->setPassword($this->password)->getPassword() : $this->password,
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
            "sex" => $this->sex?->toArray(),
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
        $array["roles"] = null;
        if($this->roles != null) {
            $array["roles"] = array();
            foreach($this->roles as $value) $array["roles"][] = $value->toArray();
        }
        $array["logs"] = null;
        if($this->logs != null) {
            $array["logs"] = array();
            foreach($this->logs as $value) $array["logs"][] = $value->toArray();
        }
        $array["punishments"] = null;
        if($this->punishments != null) {
            $array["punishments"] = array();
            foreach($this->punishments as $value) $array["punishments"][] = $value->toArray();
        }
        $array["purchases"] = null;
        if($array["purchases"] != null) {
            $array["purchases"] = array(
                "current" => null,
                "rescue" => array(),
                "rescued" => array(),
                "revoked" => array()
            );
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
        $array["tickets"] = null;
        if($this->tickets != null) {
            $array["tickets"] = array();
            foreach($this->tickets as $value) $array["tickets"][] = $value->toArray();
        }
        $array["anime_status"] = null;
        if($this->anime_status != null) {
            $array["anime_status"] = array();
            foreach($this->anime_status as $value) $array["anime_status"][] = $value->toArray();
        }
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
     */
    public function setPassword(String $password, bool $encrypt = true): User
    {
        $this->password = $encrypt ? password_hash($password, PASSWORD_DEFAULT) : $password;
        return $this;
    }

    /**
     * @param String $password
     * @return bool
     */
    public function isPassword(String $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * @param String|null $password
     * @return String|null
     */
    public static function encryptPassword(?String $password): ?String
    {
        return $password == null ? null : password_hash($password, PASSWORD_DEFAULT);
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
     * @return RolesArray
     */
    public function getRoles(): RolesArray
    {
        return $this->roles;
    }

    /**
     * @param RolesArray $roles
     * @return User
     */
    public function setRoles(RolesArray $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param Role $role
     * @return User
     * @throws NotInitialized
     */
    public function addRole(Role $role): User
    {
        if($this->roles == null) throw new NotInitialized("roles");
        $this->roles[] = $role;
        return $this;
    }

    /**
     * @param Role|null $role
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeRole(Role $role = null, int $id = null): User
    {
        if($this->roles == null) throw new NotInitialized("roles");
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
     * @throws NotInitialized
     */
    public function hasPermission(Permission $permission, String $tag = null) : bool{
        if($this->roles == null) throw new NotInitialized("roles");
        foreach($this->roles as $element){
            if($element->hasPermission($permission, $tag)) return true;
        }
        return false;
    }

    /**
     * @return PunishmentsArray
     */
    public function getPunishments(): PunishmentsArray
    {
        return $this->punishments;
    }

    /**
     * @param array $punishments
     * @return User
     */
    public function setPunishments(PunishmentsArray $punishments): User
    {
        $this->punishments = $punishments;
        return $this;
    }

    /**
     * @param Punishment $entity
     * @return User
     * @throws NotInitialized
     */
    public function addPunishment(Punishment $entity): User
    {
        if($this->punishments == null) throw new NotInitialized("punishments");
        $this->punishments[] = $entity;
        return $this;
    }

    /**
     * @param Punishment|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removePunishment(Punishment $entity = null, int $id = null): User
    {
        if($this->punishments == null) throw new NotInitialized("punishments");
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
     * @return LogsArray|null
     */
    public function getLogs(): ?LogsArray
    {
        return $this->logs;
    }

    /**
     * @param LogsArray|null $logs
     * @return User
     */
    public function setLogs(?LogsArray $logs): User
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     * @param Log $entity
     * @return User
     * @throws NotInitialized
     */
    public function addLog(Log $entity): User
    {
        if($this->logs == null) throw new NotInitialized("logs");
        $this->logs[] = $entity;
        return $this;
    }

    /**
     * @param Log|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeLog(Log $entity = null, int $id = null): User
    {
        if($this->logs == null) throw new NotInitialized("logs");
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
     * @return AccountPurchasesArray|null
     */
    public function getPurchases(): ?AccountPurchasesArray
    {
        return $this->purchases;
    }

    /**
     * @param AccountPurchasesArray|null $purchases
     * @return User
     */
    public function setPurchases(?AccountPurchasesArray $purchases): User
    {
        $this->purchases = $purchases;
        return $this;
    }

    /**
     * @param AccountPurchase $entity
     * @return User
     * @throws NotInitialized
     */
    public function addPurchase(AccountPurchase $entity): User
    {
        if($this->purchases == null) throw new NotInitialized("purchases");
        $this->purchases[] = $entity;
        return $this;
    }

    /**
     * @param AccountPurchase|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removePurchase(AccountPurchase $entity = null, int $id = null): User
    {
        if($this->purchases == null) throw new NotInitialized("purchases");
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
     * @return TicketsArray|null
     */
    public function getTickets(): ?TicketsArray
    {
        return $this->tickets;
    }

    /**
     * @param TicketsArray|null $tickets
     * @return User
     */
    public function setTickets(?TicketsArray $tickets): User
    {
        $this->tickets = $tickets;
        return $this;
    }

    /**
     * @param Ticket $entity
     * @return User
     * @throws NotInitialized
     */
    public function addTicket(Ticket $entity): User
    {
        if($this->tickets == null) throw new NotInitialized("tickets");
        $this->tickets[] = $entity;
        return $this;
    }

    /**
     * @param Ticket|null $entity
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeTicket(Ticket $entity = null, int $id = null): User
    {
        if($this->tickets == null) throw new NotInitialized("tickets");
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

    /**
     * @return array|null
     */
    public function getAnimestatus(): ?array
    {
        return $this->anime_status;
    }

    /**
     * @param array|null $anime_status
     * @return User
     */
    public function setAnimestatus(?array $anime_status): User
    {
        $this->anime_status = $anime_status;
        return $this;
    }

    /**
     * @param AnimeStatus $entity
     * @param Anime $anime
     * @return User
     * @throws NotInitialized
     */
    public function addAnimeStatus(AnimeStatus $entity, Anime $anime): User
    {
        if($this->anime_status == null) throw new NotInitialized("anime_status");
        foreach($this->anime_status as $key => $value){
            if($this->anime_status[$key]["status"]?->getId() == $entity->getId()){
                $this->anime_status[$key]["list"][] = $anime;
            }
        }
        return $this;
    }

    /**
     * @param AnimeStatus $entity
     * @param Anime|null $anime
     * @param int|null $id
     * @return $this
     * @throws NotInitialized
     */
    public function removeAnimeStatus(AnimeStatus $entity, Anime $anime = null, int $id = null): User
    {
        if($this->anime_status == null) throw new NotInitialized("anime_status");
        $remove = array();
        $status_loc = null;
        for ($i = 0; $i < count($this->anime_status); $i++) {
            if ($this->anime_status[$i]["status"]->getId() == $entity->getId()) {
                $status_loc = $i;
                foreach($this->anime_status[$i]["list"] as $key => $value){
                    if(($entity != null && $value->getId() == $anime->getId()) || ($id != null && $value->getId() == $id))
                    $remove[] = $key;
                }
            }
        }
        if($status_loc != null) foreach($remove as $item) unset($this->anime_status[$status_loc]["list"][$item]);
        return $this;
    }

    /** @noinspection PhpParamsInspection */
    public function setRelation(int $relation, EntityArray $value) : User
    {
        switch ($relation) {
            case self::ROLES:
                $this->setRoles($value);
                break;
            case self::LOGS:
                $this->setLogs($value);
                break;
            case self::PURCHASES:
                $this->setPurchases($value);
                break;
            case self::TICKETS:
                $this->setTickets($value);
                break;
            case self::ANIME_STATUS:
                //$this->setAnimestatus($value);
                break;
        }
        return $this;
    }

    /**
     * @throws NotInitialized
     * @noinspection PhpParamsInspection
     */
    public function addRelation(int $relation, Entity $value) : User
    {
        switch ($relation) {
            case self::ROLES:
                $this->addRole($value);
                break;
            case self::LOGS:
                $this->addLog($value);
                break;
            case self::PURCHASES:
                $this->addPurchase($value);
                break;
            case self::TICKETS:
                $this->addTicket($value);
                break;
            case self::ANIME_STATUS:
                //$this->addAnimeStatus($value);
                break;
        }
        return $this;
    }

    /**
     * @throws NotInitialized
     * @noinspection PhpParamsInspection
     */
    public function removeRelation(int $relation, Entity $value = null, int $id = null) : User
    {
        switch ($relation) {
            case self::ROLES:
                $this->removeRole($value, $id);
                break;
            case self::LOGS:
                $this->removeLog($value, $id);
                break;
            case self::PURCHASES:
                $this->removePurchase($value, $id);
                break;
            case self::TICKETS:
                $this->removeTicket($value, $id);
                break;
            case self::ANIME_STATUS:
                //$this->removeAnimeStatus($value, $id);
                break;
        }
        return $this;
    }
}