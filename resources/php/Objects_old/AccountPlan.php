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

class AccountPlan_old {
    // Database
    private ?Mysqli $database;

    // Flags

    public const NORMAL = 0;
    public const ALL = 1;

    // DEFAULT STRUCTURE

    private ?int $id = null;
    private ?String $name = null;
    private ?int $duration = null;
    private ?float $price = null;
    private ?int $stack = null;
    private ?int $maximum = null;
    private ?Availability $available = Availability::AVAILABLE;

    // RELATIONS

    private array $flags;

    /**
     * @param int|null $id
     * @param array $flags
     * @throws RecordNotFound
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
            $query = $database->query("SELECT * FROM account_plan WHERE id = $id;");
            if($query->num_rows > 0){
                $row = $query->fetch_array();
                $this->id = $row["id"];
                $this->name = $row["name"];
                $this->duration = $row["duration"];
                $this->price = $row["price"] != "" ? $row["price"] : 0.0;
                $this->stack = $row["stack"];
                $this->maximum = $row["maximum"];
                $this->available = $row["available"] != "" ? Availability::getItem($row["available"]) : Availability::AVAILABLE;
            } else {
                throw new RecordNotFound();
            }
        }
    }

    /**
     * This method will update the data in the database, according to the object properties
     * @return AccountPlan
     * @throws ColumnNotFound
     * @throws IOException
     * @throws InvalidSize
     * @throws NotNullable
     * @throws TableNotFound
     * @throws UniqueKey
     */
    public function store() : AccountPlan{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $database->query("START TRANSACTION");
        $query_keys_values = array(
            "id" => $this->id != null ? $this->id : Database::getNextIncrement("account_plan"),
            "name" => $this->name,
            "duration" => $this->reason ?? null,
            "price" => $this->price ?? 0,
            "stack" => $this->stack ?? null,
            "maximum" => $this->maximum ?? null,
            "available" => $this->available?->value,
        );
        foreach($query_keys_values as $key => $value) {
            if (!Database::isWithinColumnSize(value: $value, column: $key, table: "account_plan")) {
                $size = Database::getColumnSize(column: $key, table: "account_plan");
                throw new InvalidSize(column: $key, maximum: $size->getMaximum(), minimum: $size->getMinimum());
            } else if(!Database::isNullable(column: $key, table: 'account_plan') && $value == null){
                throw new NotNullable($key);
            }
        }
        if($this->id == null || $database->query("SELECT id from account_plan where id = $this->id")->num_rows == 0) {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "account_plan") && !Database::isUniqueValue(column: $key, table: "account_plan", value: $value)) throw new UniqueKey($key);
            }
            $sql_keys = "";
            $sql_values = "";
            foreach($query_keys_values as $key => $value){
                $sql_keys .= $key . ",";
                $sql_values .= ($value != null ? "'" . $value . "'" : "null") . ",";
            }
            $sql_keys = substr($sql_keys,0,-1);
            $sql_values = substr($sql_values,0,-1) ;
            $sql = "INSERT INTO account_plan ($sql_keys) VALUES ($sql_values)";
        } else {
            foreach ($query_keys_values as $key => $value) {
                if (Database::isUniqueKey(column: $key, table: "account_plan") && !Database::isUniqueValue(column: $key, table: "account_plan", value: $value, ignore_record: ["id" => $this->id])) throw new UniqueKey($key);
            }
            $update_sql = "";
            foreach($query_keys_values as $key => $value){
                $update_sql .= ($key . " = " . ($value != null ? "'" . $value . "'" : "null")) . ",";
            }
            $update_sql = substr($update_sql,0,-1);
            $sql = "UPDATE account_plan SET $update_sql WHERE id = $this->id";
        }
        $database->query($sql);
        $database->query("COMMIT");
        return $this;
    }

    /**
     * This method will remove the object from the database.
     * @return AccountPlan
     * @throws IOException
     */
    public function remove() : AccountPlan{
        if ($this->database == null) throw new IOException("Could not access database services.");
        $database = $this->database;
        $this->available = Availability::NOT_AVAILABLE;
        $sql = "UPDATE account_plan SET available = '$this->available->value' WHERE id = $this->id";
        $database->query($sql);
        return $this;
    }

    /**
     * @param int|null $id
     * @param int|null $name
     * @param float|null $duration
     * @param Availability $available
     * @param string|null $sql
     * @param array $flags
     * @return array
     * @throws RecordNotFound
     * @throws \ReflectionException
     */
    public static function find(int $id = null, int $name = null, float $duration = null, Availability $available = Availability::AVAILABLE, string $sql = null, array $flags = [self::NORMAL]) : array{
        $result = array();
        try {
            $database = Database::getConnection();
        } catch(IOException $e){
            return $result;
        }
        if($sql != null){
            $sql_command = "SELECT id from account_plan WHERE " . $sql;
        } else {
            $sql_command = "SELECT id from account_plan WHERE " .
                ($id != null ? "(id != null AND id = '$id')" : "") .
                ($name != null ? "(name != null AND name = '$name')" : "") .
                ($duration != null ? "(duration != null AND duration = '$duration')" : "") .
                ($available != null ? "(available != null AND available = '$available->value')" : "");
            $sql_command = str_replace($sql_command, ")(", ") AND (");
            if(str_ends_with($sql_command, "WHERE ")) $sql_command = str_replace($sql_command, "WHERE ", "");
        }
        $query = $database->query($sql_command);
        while($row = $query->fetch_array()){
            $result[] = new AccountPlan($row["id"], $flags);
        }
        return $result;
    }

    #[ArrayShape(["id" => "int|mixed|null", "name" => "mixed|null|String", "duration" => "int|mixed|null", "price" => "float|mixed|null", "stack" => "int|mixed|null", "maximum" => "int|mixed|null", "available" => "array|null"])]
    #[Pure]
    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "duration" => $this->duration,
            "price" => $this->price,
            "stack" => array(
                "value" => $this->stack,
                "isStackable" => $this->isStackable() ? 'true' : 'false',
                "isUnlimited" => $this->isUnlimitedStackable() ? 'true' : 'false'
            ),
            "maximum" => array(
                "value" => $this->maximum,
                "isPurchasable" => $this->isPurchasable() ? 'true' : 'false',
                "isUnlimited" => $this->isUnlimitedPurchasable() ? 'true' : 'false'
            ),
            "available" => $this->available?->toArray()
        );
    }

    /**
     * @return bool
     */
    public function isPurchasable() : Bool{
        return ($this->maximum != null && $this->maximum != "" && $this->maximum >= 0);
    }

    /**
     * @return bool
     */
    public function isUnlimitedPurchasable() : Bool{
        return ($this->maximum != null && $this->maximum != "" &&$this->maximum == 0);
    }

    /**
     * @return bool
     */
    #[Pure]
    public function isStackable() : Bool{
        return ($this->stack != null && $this->stack != "" && $this->stack >= 0 && $this->isPurchasable());
    }

    /**
     * @return bool
     */
    #[Pure]
    public function isUnlimitedStackable() : Bool{
        return ($this->maximum != null && $this->maximum != "" && $this->stack == 0 && $this->isPurchasable());
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
     * @return mixed|String|null
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed|String|null $name
     * @return AccountPlan
     */
    public function setName(mixed $name): AccountPlan
    {
        $this->name = $name;
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
     * @return AccountPlan
     */
    public function setDuration(mixed $duration): AccountPlan
    {
        $this->duration = $duration;
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
     * @return AccountPlan
     */
    public function setPrice(mixed $price): AccountPlan
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getStack(): mixed
    {
        return $this->stack;
    }

    /**
     * @param int|mixed|null $stack
     * @return AccountPlan
     */
    public function setStack(mixed $stack): AccountPlan
    {
        if($stack == null || $stack < -1) $stack = -1;
        $this->stack = $stack;
        return $this;
    }

    /**
     * @return int|mixed|null
     */
    public function getMaximum(): mixed
    {
        return $this->maximum;
    }

    /**
     * @param int|mixed|null $maximum
     * @return AccountPlan
     */
    public function setMaximum(mixed $maximum): AccountPlan
    {
        if($maximum == null || $maximum < -1) $maximum = -1;
        $this->maximum = $maximum;
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
     * @return AccountPlan
     */
    public function setAvailable(?Availability $available): AccountPlan
    {
        $this->available = $available;
        return $this;
    }
}
?>