<?php

$snake_case = 1;
$camelCase = 2;

class A 
{
    public int $property_name = 1;
    public int $propertyName = 2;

    public function methodA(int $variable_name)
    {
        $snake_case = 1;
        $camelCase = 2;
    }

    public function methodB(int $variableName)
    {
        $snake_case = 1;
        $camelCase = 2;
    }
}
