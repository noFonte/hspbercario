<?php

namespace Domain\UseCases;

use Application\DoctorInput;
use Infra\Repositories\IDoctorRepository;
 
 
class UpdateDoctorUseCase{

    private IDoctorRepository $doctorRepository;

    public  function __construct(IDoctorRepository $doctorRepository){
        $this->doctorRepository =  $doctorRepository;
        
    }

    public function execute($code,DoctorInput $doctor){
      
        return  $this->doctorRepository->update($code,$doctor);

    }
}