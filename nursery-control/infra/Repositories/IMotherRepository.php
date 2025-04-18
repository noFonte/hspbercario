<?php


namespace Infra\Repositories;

use Application\MotherInput;


interface IMotherRepository{
    function created(MotherInput $mother);
    function update($code,MotherInput $mother);
    function byCode($code);
}