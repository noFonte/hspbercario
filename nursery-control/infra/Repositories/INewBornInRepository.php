<?php

namespace Infra\Repositories;

use Application\NewBornInput;

interface INewBornInRepository{
    
    function created(NewBornInput $newborn);
    function update($code,NewBornInput $newborn);
    function byCode($code);
    function all();
}