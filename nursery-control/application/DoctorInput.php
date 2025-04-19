<?php

namespace Application;



class DoctorInput
{
    private $inputs = ["code", "crm", "name", "cellphone", "specialty"];

    public $code;
    public $name;
    public $crm;
    public $phone;
    public $cellphone;
    public $specialty;

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
