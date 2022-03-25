<?php
namespace Objects;
/*
 * Class imports
 */

use DateTime;
use JetBrains\PhpStorm\ArrayShape;
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
use Exceptions\NotNullable;

/*
 * Enumerator Imports
 */
use Enumerators\Availability;
/*
 * Others
 */
use Functions\Database;

class AccountPurchase_old {
    // Database
    private ?Mysqli $database;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?AccountPlan $plan = null;
    private ?float $price = null;
    private ?DateTime $purchased_on = null;
    private ?int $duration = null;
    private ?User $revoked_by = null;
    private ?String $revoked_reason = null;
    private ?DateTime $revoked_at = null;
    private ?DateTime $rescued_at = null;

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
            $query = $database->query("SELECT * FROM account_purchase WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->plan = $row["plan"] != "" ? new AccountPlan($row["plan"]) : null;
                $this->price = $row["price"];
                $this->purchased_on = $row["purchased_on"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["purchased_on"]) : null;
                $this->duration = $row["duration"];
                $this->revoked_by = $row["revoked_by"] != "" ? new User($row["revoked_by"]) : null;
                $this->revoked_reason = $row["revoked_reason"];
                $this->revoked_at = $row["revoked_at"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["revoked_at"]) : null;
                $this->rescued_at = $row["rescued_at"] != "" ? DateTime::createFromFormat(Database::DateFormat, $row["rescued_at"]) : null;
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
     * @throws NotNullable
     */
    public function store(User $user) : AccountPurchase{
        if ($this->database == null) throw new IOException("Could not access database services.");
        if (!isset($user)) throw new NotNullable(argument: 'user');
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("account_purchase"),
            "user" => $user->getId(),
            "plan" => $this->plan?->store()->getId(),
            "price" => $this->price ?? null,
            "purchased_on" => $this->purchased_on?->format(Database::DateFormat),
            "duration" => $this->duration ?? null,
            "revoked_by" => $this->revoked_by?->store()->getId(),
            "revoked_reason" => $this->revoked_reason ?? null,
            "revoked_at" => $this->revoked_at?->format(Database::DateFormat),
            "rescued_at" => $this->rescued_at?->format(Database::DateFormat)
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "account_purchase")) {
                $size = Database::getColumnSize(column: $key, table: "account_purchase");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'account_purchase') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from account_purchase where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "account_purchase") && !Database::isUniqueValue(column: $key, table: "account_purchase", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO account_purchase ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "account_purchase") && !Database::isUniqueValue(column: $key, table: "account_purchase", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE account_purchase SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return $this
     * @throws IOException
     */
    public function remove() : AccountPurchase{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("DELETE FROM account_purchase where id = $this->id");
        return $this;
    }

    /**
     * @param int|null $id
     * @param int|null $user
     * @param int|null $plan
     * @param int|null $revoked_by
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws MalformedJSON
     * @throws RecordNotFound
     */
    public static function find(int $id = null, int $user = null, int $plan = null, int $revoked_by = null, string $sql = null, array $flags = [self::NORMAL]) : array{
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
                ($plan != null ? "(plan != null AND plan = '$plan')" : "") .
                ($revoked_by != null ? "(revoked_by != null AND revoked_by = '$revoked_by')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new AccountPurchase($row["id"], $flags);
        }
        return $result;
    }


    #[ArrayShape(["id" => "int|mixed|null", "plan" => "array|null", "price" => "float|mixed|null", "purchased_on" => "null|string", "duration" => "int|mixed|null", "revoked_by" => "array|null", "revoked_reason" => "mixed|null|String", "revoked_at" => "null|string", "rescued_at" => "null|string"])]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "plan" => $this->plan?->toArray(),
            "price" => $this->price,
            "purchased_on" => $this->purchased_on?->format(Database::DateFormat),
            "duration" => $this->duration,
            "revoked_by" => $this->revoked_by?->toArray(),
            "revoked_reason" => $this->revoked_reason,
            "revoked_at" => $this->revoked_at?->format(Database::DateFormat),
            "rescued_at" => $this->rescued_at?->format(Database::DateFormat)
        );
    }

    public function isActive() : bool
    {
        return $this->revoked_by == null && $this->rescued_at != null && $this->duration != null && ((new DateTime)->getTimestamp() <= $this->rescued_at->getTimestamp() + $this->duration);
    }

    public function isRedeemable() : bool
    {
        return $this->revoked_by == null && !$this->isActive();
    }

    public function isRescued() : bool
    {
        return $this->revoked_by == null && $this->rescued_at != null;
    }

    public function isRevoked() : bool
    {
        return $this->revoked_by != null;
    }

    /**
     * @return int|mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @return AccountPlan|null
     */
    public function getPlan(): ?AccountPlan
    {
        return $this->plan;
    }

    /**
     * @param AccountPlan|null $plan
     * @return AccountPurchase
     */
    public function setPlan(?AccountPlan $plan): AccountPurchase
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * @return float|mixed|null
     */
    public function getPrice(): mixed
    {
        return $this->price;
    }

    /**
     * @param float|mixed|null $price
     * @return AccountPurchase
     */
    public function setPrice(mixed $price): AccountPurchase
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getPurchasedOn(): DateTime|bool|null
    {
        return $this->purchased_on;
    }

    /**
     * @param DateTime|String $purchased_on
     * @return AccountPurchase
     */
    public function setCreationDate(DateTime|String $purchased_on): AccountPurchase
    {
        if(is_string($purchased_on)){
            $this->purchased_on = DateTime::createFromFormat(Database::DateFormat, $purchased_on);
        } else if(is_a($purchased_on, "DateTime")){
            $this->purchased_on = $purchased_on;
        }
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getDuration(): mixed
    {
        return $this->duration;
    }

    /**
     * @param int|mixed|null $duration
     * @return AccountPurchase
     */
    public function setDuration(mixed $duration): AccountPurchase
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return \Objects\User|null
     */
    public function getRevokedBy(): ?\Objects\User
    {
        return $this->revoked_by;
    }

    /**
     * @param \Objects\User|null $revoked_by
     * @return AccountPurchase
     */
    public function setRevokedBy(?\Objects\User $revoked_by): AccountPurchase
    {
        $this->revoked_by = $revoked_by;
        return $this;
    }

    /**
     * @return mixed|String|null
     */
    public function getRevokedReason(): mixed
    {
        return $this->revoked_reason;
    }

    /**
     * @param mixed|String|null $revoked_reason
     * @return AccountPurchase
     */
    public function setRevokedReason(mixed $revoked_reason): AccountPurchase
    {
        $this->revoked_reason = $revoked_reason;
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getRevokedAt(): DateTime|bool|null
    {
        return $this->revoked_at;
    }

    /**
     * @param DateTime|String $revoked_at
     * @return AccountPurchase
     */
    public function setRevokedAt(DateTime|String $revoked_at): AccountPurchase
    {
        if(is_string($revoked_at)){
            $this->revoked_at = DateTime::createFromFormat(Database::DateFormat, $revoked_at);
        } else if(is_a($revoked_at, "DateTime")){
            $this->revoked_at = $revoked_at;
        }
        return $this;
    }

    /**
     * @return DateTime|false|null
     */
    public function getRescuedAt(): DateTime|bool|null
    {
        return $this->rescued_at;
    }

    /**
     * @param DateTime|String $rescued_at
     * @return AccountPurchase
     */
    public function setRescuedAt(DateTime|String $rescued_at): AccountPurchase
    {
        if(is_string($rescued_at)){
            $this->rescued_at = DateTime::createFromFormat(Database::DateFormat, $rescued_at);
        } else if(is_a($rescued_at, "DateTime")){
            $this->rescued_at = $rescued_at;
        }
        return $this;
    }
}
?>