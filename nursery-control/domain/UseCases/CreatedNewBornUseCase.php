<?php

namespace Domain\UseCases;

use Application\NewBornInput;
use Infra\Repositories\INewBornInRepository;
 
class CreatedNewBornUseCase{

    private INewBornInRepository $newbornRepository;

    public  function __construct(INewBornInRepository $newbornRepository){
        $this->newbornRepository =  $newbornRepository;
        
    }

    public function execute(NewBornInput $newborn){
        return   $this->newbornRepository->created($newborn);

    }
}