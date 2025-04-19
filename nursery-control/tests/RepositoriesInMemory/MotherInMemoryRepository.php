<?php

use Application\MotherInput;
use Infra\Repositories\IMotherRepository;
use Infra\Repositories\Entities\MotherEntity;
use Infra\Exceptions\MotherAlreadyExistsException;

 

class MotherInMemoryRepository implements IMotherRepository
{

    private $tablemothers = [];
    function created(MotherInput $mother)
    {
        if (!is_null($this->byCode($mother->code))) {
            throw new MotherAlreadyExistsException("duplicate record");
        }
        $motherEntity =  new MotherEntity($mother->toArray());
        $this->tablemothers[] = $motherEntity;
        return true;
    }
    function update($code, MotherInput $mother)
    {
        foreach ($this->tablemothers as $key => $row) {
            if ($this->byCode($code)) {
                $motherEntity =  new MotherEntity($mother->toArray());
                $this->tablemothers[$key] = $motherEntity;
                return true;
            }
        }

        return false;
    }

    function byCode($code)
    {
        foreach ($this->tablemothers as $key => $row) {
            if ($row->code == $code) {
                return new MotherInput($row->toArray());
            }
        }
        return null;
    }
}