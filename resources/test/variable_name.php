<?php
class variable_name
{
    private String $test;

    /**
     * @param string $variable
     * @param mixed $value
     */
    public function __construct(string $variable, mixed $value)
    {
        $this->{$variable} = $value;
    }

    /**
     * @return String
     */
    public function getTest(): string
    {
        return $this->test;
    }

    /**
     * @param String $test
     */
    public function setTest(string $test): void
    {
        $this->test = $test;
    }


}

$var = new variable_name('test', "upsi");
echo $var->getTest();