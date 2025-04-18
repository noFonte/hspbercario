<?php

namespace Application;


class MotherInput{


    private $inputs=["code","name","address","phone","dateofbirth"];

    public $code;
    public $name;
    public $address;
    public $phone;
    public $dateofbirth;

    public function __construct($input=null){

        if(!is_null($input) && is_array($input)){

            foreach($this->inputs as $value){
                if(isset($input[$value])){
                    $this->$value=$input[$value];
                }
            }
        }
    }


    public function toArray(){

        $props =[];
 
        foreach($this->inputs as $value){
            $props[$value]=$this->$value;
        }
        return $props;
    }


}