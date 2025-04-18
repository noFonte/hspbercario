<?php


namespace Infra\Repositories\Entities;

use Application\MotherInput;


interface IMotherRepository{
    function created(MotherInput $mother);
    function update($code,MotherInput $mother);

}