<?php

namespace Domain\UseCases;

use Application\MotherInput;
use Infra\Repositories\IMotherRepository;

 



class UpdateMotherUseCase{

    private IMotherRepository $motherRepository;

    public  function __construct(IMotherRepository $motherRepository){
        $this->motherRepository =  $motherRepository;
        
    }

    public function execute($code,MotherInput $mother){
      
        return  $this->motherRepository->update($code,$mother);

    }
}