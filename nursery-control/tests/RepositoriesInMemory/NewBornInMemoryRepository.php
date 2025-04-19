<?php

use Application\NewBornInput;
use Infra\Repositories\INewBornInRepository;
use Infra\Repositories\Entities\NewbornEntity;
use Infra\Exceptions\NewbornAlreadyExistsException;

 

 


class NewBornInMemoryRepository implements INewBornInRepository{

    private $tablenewborns = [];
    
    function created(NewBornInput $newborn){
        if (!is_null($this->byCode($newborn->code))) {
            throw new NewbornAlreadyExistsException("duplicate record");
        }
        $newbornEntity =  new NewbornEntity($newborn->toArray());
        $this->tablenewborns[] = $newbornEntity;
        return true;

    }
    function update($code,NewBornInput $newborn){
        foreach ($this->tablenewborns as $key => $row) {
            if ($this->byCode($code)) {
                $newbornEntity =  new NewbornEntity($newborn->toArray());
                $this->tablenewborns[$key] = $newbornEntity;
                return true;
            }
        }

        return false;
    }
    function byCode($code){
        foreach ($this->tablenewborns as $key => $row) {
            if ($row->code == $code) {
                return new NewBornInput($row->toArray());
            }
        }
        return null;
    }
    function all(){}

 

}

