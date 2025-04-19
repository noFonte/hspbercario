<?php

use Application\DoctorInput;
use Infra\Repositories\IDoctorRepository;
use Infra\Repositories\Entities\DoctorEntity;
use Infra\Exceptions\DoctorAlreadyExistsException;


class DoctorInMemoryRepository implements IDoctorRepository{
    private $tabledoctors = [];
    function created(DoctorInput $doctor){
        if (!is_null($this->byCode($doctor->code))) {
            throw new DoctorAlreadyExistsException("duplicate record");
        }
        $doctorEntity =  new DoctorEntity($doctor->toArray());
        $this->tabledoctors[] = $doctorEntity;
        return true;

    }
    function update($code,DoctorInput $doctor){
        foreach ($this->tabledoctors as $key => $row) {
            if ($this->byCode($code)) {
                $doctorEntity =  new DoctorEntity($doctor->toArray());
                $this->tabledoctors[$key] = $doctorEntity;
                return true;
            }
        }

        return false;
    }
    function byCode($code){
        foreach ($this->tabledoctors as $key => $row) {
            if ($row->code == $code) {
                return new DoctorInput($row->toArray());
            }
        }
        return null;
    }
    function all(){
        $doctors=[];
        foreach ($this->tabledoctors as $key => $row) {
            $doctor=new DoctorInput($row->toArray());
            $doctors[]= $doctor->toArray();
        }
        return  $doctors;
    }

}
