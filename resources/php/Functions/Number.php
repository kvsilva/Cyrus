<?php

namespace Functions;

use JetBrains\PhpStorm\Pure;

class Size{
    private float $maximum;
    private float $minimum;

    /**
     * @param float|int $maximum
     * @param float|int $minimum
     */
    public function __construct(float|int $maximum, float|int $minimum = 0)
    {
        $this->maximum = $maximum;
        $this->minimum = $minimum;
    }

    /**
     * @return float|int
     */
    public function getMaximum(): float|int
    {
        return $this->maximum;
    }

    /**
     * @param float|int $maximum
     * @return Size
     */
    public function setMaximum(float|int $maximum): Size
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * @return float|int
     */
    public function getMinimum(): float|int
    {
        return $this->minimum;
    }

    /**
     * @param float|int $minimum
     * @return Size
     */
    public function setMinimum(float|int $minimum): Size
    {
        $this->minimum = $minimum;
        return $this;
    }


}

class Number
{
    #[Pure]
    public static function getUnsignedRange($byte = 1) : Size{
        return new Size(pow(2, ($byte * 8))-1, 0);
    }
    #[Pure]
    public static function getSigned($byte = 1) : Size{

        return new Size(pow(2, ($byte * 8)-1)-1, 0 - pow(2, ($byte * 8)-1));
    }

    #[Pure]
    public static function getRequiredByteSize($value, $signed = true): int
    {
        $bytes = 0;
        do{
            $bytes++;
            $maximum = $signed ? self::getSigned($bytes)->getMaximum() : self::getUnsignedRange($bytes)->getMaximum();
            $minimum = $signed ? self::getSigned($bytes)->getMinimum() : self::getUnsignedRange($bytes)->getMinimum();
        } while ($maximum < $value || $minimum > $value);
        return $bytes;
    }
}