<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use JetBrains\PhpStorm\Pure;
use mysqli;

/*
 * Object Imports
 */

use Objects\LogAction;
use Objects\User;

/*
 * Exception Imports
 */
use Exceptions\UniqueKey;
use Exceptions\RecordNotFound;
use Exceptions\IOException;
use Exceptions\MalformedJSON;
use Exception;
use Exceptions\ColumnNotFound;
use Exceptions\InvalidSize;
use Exceptions\TableNotFound;

/*
 * Enumerator Imports
 */
use Enumerators\Availability;
/*
 * Others
 */
use Functions\Database;

class Punishment {
    // Database
    private ?Mysqli $database;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?User $user = null;
    private ?PunishmentType $punishment_type = null;
    private ?String $reason = null;
    private ?DateTime $lasts_until = null;
    private ?DateTime $creation_date = null;
    private ?User $performed_by = null;
    private ?User $revoked_by = null;
    private ?String $revoked_reason = null;
    private ?Availability $available = Availability::AVAILABLE;

    // RELATIONS

    private array $flags;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
     * @throws MalformedJSON
     * @throws Exception
     */
    function __construct(int $id = null, array $flags = array(self::NORMAL)) {
        $this->flags = $flags;
        try {
            $this->database = Database::getConnection();
        } catch(IOException $e){
            $this->database = null;
        }
        if($id != null && $this->database != null){
            $database = $this->database;
            $query = $database->query("SELECT * FROM punishment WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->user = $row["user"] != "" ? new User($row["user"]) : null;
                $this->punishment_type = new PunishmentType($row["punishment_type"]);
                $this->reason = $row["reason"];
                $this->lasts_until = $row["lasts_until"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["lasts_until"]) : null;
                $this->creation_date = $row["creation_date"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["creation_date"]) : null;
                $this->performed_by = $row["performed_by"] != "" ? new User($row["performed_by"]) : null;
                $this->revoked_by = $row["revoked_by"] != "" ? new User($row["performed_by"]) : null;
                $this->revoked_reason = $row["revoked_reason"];
                $this->available = $row["available"] != "" ? Availability::getAvailability($row["available"]) : Availability::AVAILABLE;
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return $this
     * @throws IOException
     * @throws InvalidSize
     * @throws UniqueKey
     * @throws ColumnNotFound
     * @throws TableNotFound
     */
    public function store() : Punishment{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $query_keys_values = array(
            "id" => $this->id,
            "user" => isset($this->user) ? $this->user->store()->getId() : null,
            "punishment_type" => isset($this->punishment_type) ? $this->punishment_type->store()->getId() : null,
            "reason" => $this->reason ?? null,
            "lasts_until" => isset($this->lasts_until) ? $this->lasts_until->format(Database::DateFormat) : null,
            "creation_date" => isset($this->creation_date) ? $this->creation_date->format(Database::DateFormat) : null,
            "performed_by" => isset($this->performed_by) ? $this->performed_by->store()->getId() : null,
            "revoked_by" => isset($this->revoked_by) ? $this->revoked_by->store()->getId() : null,
            "revoked_reason" => $this->revoked_reason ?? null,
            "available" => isset($this->available) ? $this->available->value : null,
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "punishment")) {
                $size = Database::getColumnSize(column: $key, table: "punishment");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            }
        }
        if($this->id == null || $database->query("SELECT id from punishment where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "punishment") && !Database::isUniqueValue(column: $key, table: "punishment", value: $value)) throw new UniqueKey($key);
            }
            $this->id = Database::getNextIncrement("punishment");
            $query_keys_values["id"] = $this->id;
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO punishment ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "punishment") && !Database::isUniqueValue(column: $key, table: "punishment", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE punishment SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     */
    public function remove() : Punishment{
        $database = $this->database;
        $database->query("DELETE FROM log where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param int|null $user
     * @param int|null $punishment_type
     * @param int|null $performed_by
     * @param int|null $revoked_by
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws MalformedJSON
     * @throws RecordNotFound
     */
    public static function find(int $id = null, int $user = null, int $punishment_type = null, int $performed_by = null, int $revoked_by = null, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from punishment WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from punishment WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($user != null ? "(user != null AND user = '$user')" : "") .
                ($punishment_type != null ? "(punishment_type != null AND punishment_type = '$punishment_type')" : "") .
                ($performed_by != null ? "(performed_by != null AND performed_by = '$performed_by')" : "")
                ($revoked_by != null ? "(revoked_by != null AND revoked_by = '$revoked_by')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        $result = array();
        while($row = $query->fetch_array()){
            $result[] = new Punishment($row["id"], $flags);
        }
        return $result;
    }

    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "user" => isset($this->user) ? $this->user->toArray() : null,
            "punishment_type" => isset($this->punishment_type) ? $this->punishment_type->toArray() : null,
            "reason" => $this->reason,
            "lasts_until" => $this->lasts_until,
            "creation_date" => $this->creation_date,
            "performed_by" => isset($this->performed_by) ? $this->performed_by->toArray() : null,
            "revoked_by" => isset($this->revoked_by) ? $this->revoked_by->toArray() : null,
            "revoked_reason" => $this->revoked_reason,
            "available" => isset($this->available) ? $this->available->toArray() : null
        );
    }
    /**
     * @return int|mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Punishment
     */
    public function setUser(User $user): Punishment
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return PunishmentType
     */
    public function getPunishmentType(): PunishmentType
    {
        return $this->punishment_type;
    }

    /**
     * @param PunishmentType $punishment_type
     * @return Punishment
     */
    public function setPunishmentType(PunishmentType $punishment_type): Punishment
    {
        $this->punishment_type = $punishment_type;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getReason(): mixed
    {
        return $this->reason;
    }

    /**
     * @param mixed|String $reason
     * @return Punishment
     */
    public function setReason(mixed $reason): Punishment
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastsUntil(): DateTime
    {
        return $this->lasts_until;
    }

    /**
     * @param DateTime $lasts_until
     * @return Punishment
     */
    public function setLastsUntil(DateTime $lasts_until): Punishment
    {
        $this->lasts_until = $lasts_until;
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
     * @param DateTime $creation_date
     * @return Punishment
     */
    public function setCreationDate(DateTime $creation_date): Punishment
    {
        $this->creation_date = $creation_date;
        return $this;
    }

    /**
     * @return User
     */
    public function getPerformedBy(): User
    {
        return $this->performed_by;
    }

    /**
     * @param User $performed_by
     * @return Punishment
     */
    public function setPerformedBy(User $performed_by): Punishment
    {
        $this->performed_by = $performed_by;
        return $this;
    }

    /**
     * @return User
     */
    public function getRevokedBy(): User
    {
        return $this->revoked_by;
    }

    /**
     * @param User $revoked_by
     * @return Punishment
     */
    public function setRevokedBy(User $revoked_by): Punishment
    {
        $this->revoked_by = $revoked_by;
        return $this;
    }

    /**
     * @return mixed|String
     */
    public function getRevokedReason(): mixed
    {
        return $this->revoked_reason;
    }

    /**
     * @param mixed|String $revoked_reason
     * @return Punishment
     */
    public function setRevokedReason(mixed $revoked_reason): Punishment
    {
        $this->revoked_reason = $revoked_reason;
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
     * @return Punishment
     */
    public function setAvailable(?Availability $available): Punishment
    {
        $this->available = $available == null ? Availability::NOT_AVAILABLE : $available;
        return $this;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }
}
?>