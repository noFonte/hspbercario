<?php

namespace Domain\UseCases;

use Application\DoctorInput;
use Infra\Repositories\IDoctorRepository;

 
 



class CreatedDoctorUseCase{

    private IDoctorRepository $doctorRepository;

    public  function __construct(IDoctorRepository $doctorRepository){
        $this->doctorRepository =  $doctorRepository;
        
    }

    public function execute(DoctorInput $doctor){
        return   $this->doctorRepository->created($doctor);

    }
}