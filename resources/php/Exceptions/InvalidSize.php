<?php
namespace Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use ReturnTypeWillChange;

class InvalidSize extends Exception
{
    private String $column;
    private int $maximum;
    private int $minimum;
    #[Pure]
    public function __construct(String $column, int $maximum, int $minimum = 0) {
        parent::__construct("The size of the value provided does not meet the column limits in the database (column: ". $column . ", maximum: " . $maximum . ", minimum: " . $minimum . ").");
        $this->column = $column;
        $this->maximum = $maximum;
        $this->minimum = $minimum;
    }

    #[ReturnTypeWillChange]
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * @return String
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getMaximum(): int
    {
        return $this->maximum;
    }

    /**
     * @return int
     */
    public function getMinimum(): int
    {
        return $this->minimum;
    }

}