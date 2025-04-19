<?php

namespace Infra\Repositories;

use Application\DoctorInput;

interface IDoctorRepository{
    
    function created(DoctorInput $doctor);
    function update($code,DoctorInput $doctor);
    function byCode($code);
    function all();
}