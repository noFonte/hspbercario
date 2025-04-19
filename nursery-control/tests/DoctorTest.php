<?php

use Application\DoctorInput;
use PHPUnit\Framework\TestCase;
use Domain\UseCases\UpdateDoctorUseCase;
use Domain\UseCases\CreatedDoctorUseCase;
use Domain\UseCases\ListAllDoctorsUseCase;
use Infra\Exceptions\DoctorAlreadyExistsException;


require_once("RepositoriesInMemory/DoctorInMemoryRepository.php");


class DoctorTest extends TestCase
{
    function testCreatedDoctor()
    {
        $doctorMemory = new DoctorInMemoryRepository();
        $doctor = new DoctorInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Medico_%s", uniqid()),
                "crm" => sprintf("crm-%s", uniqid()),
                "phone" => rand(9999990, 9999999),
                "cellphone" =>  rand(9999990, 9999999),
                "specialty" =>  sprintf("habilidade-%s", uniqid()),

            ]
        );
        $createddoctorUseCase =  new CreatedDoctorUseCase($doctorMemory);
        $output = $createddoctorUseCase->execute($doctor);
        $this->assertEquals(true, $output);
    }


    function testFailHasDoctorRegistred()
    {
        $this->expectException(DoctorAlreadyExistsException::class);

        $doctorMemory = new DoctorInMemoryRepository();
        $doctor = new DoctorInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Medico_%s", uniqid()),
                "crm" => sprintf("crm-%s", uniqid()),
                "phone" => rand(9999990, 9999999),
                "cellphone" =>  rand(9999990, 9999999),
                "specialty" =>  sprintf("habilidade-%s", uniqid()),

            ]
        );
        $createddoctorUseCase =  new CreatedDoctorUseCase($doctorMemory);
        $createddoctorUseCase->execute($doctor);
      
        $createddoctorUseCase =  new CreatedDoctorUseCase($doctorMemory);
        $createddoctorUseCase->execute($doctor);
    }


    function testUpdateDoctor()
    {
        $doctorMemory = new DoctorInMemoryRepository();
        $doctor = new DoctorInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Medico_%s", uniqid()),
                "crm" => sprintf("crm-%s", uniqid()),
                "phone" => rand(9999990, 9999999),
                "cellphone" =>  rand(9999990, 9999999),
                "specialty" =>  sprintf("habilidade-%s", uniqid()),

            ]
        );
        $createddoctorUseCase =  new CreatedDoctorUseCase($doctorMemory);
        $output = $createddoctorUseCase->execute($doctor);
        $this->assertEquals(true, $output);
        $doctor->specialty = sprintf("habilidade-%s", uniqid());
        $updatedoctorUseCase = new UpdateDoctorUseCase($doctorMemory);
        $output = $updatedoctorUseCase->execute($doctor->code, $doctor);
        $this->assertEquals(true, $output);
    }


    function testListAllDoctors()
    {

        $sizeDoctors = 1000;
        $doctorMemory = new DoctorInMemoryRepository();
        $doctors = [];
        for ($next = 0; $next < $sizeDoctors; $next++) {
            $doctor = new DoctorInput(
                [
                    "code" => uniqid(),
                    "name" => sprintf("Medico_%s", uniqid()),
                    "crm" => sprintf("crm-%s", uniqid()),
                    "phone" => rand(9999990, 9999999),
                    "cellphone" =>  rand(9999990, 9999999),
                    "specialty" =>  sprintf("habilidade-%s", uniqid()),

                ]
            );

            $createddoctorUseCase =  new CreatedDoctorUseCase($doctorMemory);
            $output = $createddoctorUseCase->execute($doctor);
            $this->assertEquals(true, $output);
            $doctors[] = $doctor;
        }

        $listAllDoctorsUseCase = new ListAllDoctorsUseCase($doctorMemory);
        $output = $listAllDoctorsUseCase->execute();
        $this->assertEquals($sizeDoctors, count($output));
        $this->assertEquals($doctors[0]->code, $output[0]['code']);
        $this->assertEquals($doctors[($sizeDoctors-1)]->code, $output[($sizeDoctors-1)]['code']);
    }
}
