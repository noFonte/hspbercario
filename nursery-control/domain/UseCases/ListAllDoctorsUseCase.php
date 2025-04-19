<?php

namespace Domain\UseCases;

use Application\DoctorInput;
use Infra\Repositories\IDoctorRepository;
 
 
class ListAllDoctorsUseCase{

    private IDoctorRepository $doctorRepository;

    public  function __construct(IDoctorRepository $doctorRepository){
        $this->doctorRepository =  $doctorRepository;
        
    }

    public function execute(){
      
        return  $this->doctorRepository->all();

    }
}