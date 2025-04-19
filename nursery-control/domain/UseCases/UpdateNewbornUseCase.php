<?php

namespace Domain\UseCases;

use Application\NewBornInput;
use Infra\Repositories\INewBornInRepository;

 
class UpdateNewbornUseCase{
    private INewBornInRepository $newBornRepository;
    public  function __construct(INewBornInRepository $newBornRepository){
        $this->newBornRepository =  $newBornRepository;
    }
    public function execute($code,NewBornInput $newBorn){
        return $this->newBornRepository->update($code,$newBorn);

    }
}