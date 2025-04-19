<?php

namespace Infra\Repositories\Entities;


class NewbornEntity
{
    private $inputs = ["code", "name", "dateofbirth", "birthweight","height","associatedwithMother"];

    public $code;
    public $name;
    public $dateofbirth;
    public $birthweight;
    public $height;
    public $associatedwithMother;

    public function __construct($input = null)
    {

        if (!is_null($input) && is_array($input)) {

            foreach ($this->inputs as $value) {
                if (isset($input[$value])) {
                    $this->$value = $input[$value];
                }
            }
        }
    }


    public function toArray()
    {

        $props = [];

        foreach ($this->inputs as $value) {
            $props[$value] = $this->$value;
        }
        return $props;
    }
}
