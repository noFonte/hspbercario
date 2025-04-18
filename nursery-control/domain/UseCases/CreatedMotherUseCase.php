<?php

namespace Domain\UseCases;

use Application\MotherInput;
use Infra\Repositories\IMotherRepository;
 



class CreatedMotherUseCase{

    private IMotherRepository $motherRepository;

    public  function __construct(IMotherRepository $motherRepository){
        $this->motherRepository =  $motherRepository;
        
    }

    public function execute(MotherInput $mother){
       

        return   $this->motherRepository->created($mother);

    }
}