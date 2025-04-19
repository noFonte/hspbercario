<?php

use Application\MotherInput;
use Application\NewBornInput;
use MotherInMemoryRepository;
use NewBornInMemoryRepository;
use PHPUnit\Framework\TestCase;
use Domain\UseCases\CreatedMotherUseCase;
use Domain\UseCases\UpdateNewbornUseCase;
use Domain\UseCases\CreatedNewBornUseCase;
use Infra\Exceptions\NewbornAlreadyExistsException;



require_once("libs.php");
require_once("RepositoriesInMemory/MotherInMemoryRepository.php");
require_once("RepositoriesInMemory/NewBornInMemoryRepository.php");

class NewbornTest  extends TestCase
{
   function testCreatednewborn()
    {
        $newbornMemory = new NewBornInMemoryRepository();
        $motherMemory = new MotherInMemoryRepository();
        $mothers = [];
        $newbornBirth = date("Y") - 30;
        for ($next = 0; $next < 2000; $next++) {
            $mother  = new MotherInput(
                [
                    "code" => uniqid(),
                    "name" => sprintf("Mae_%s", uniqid()),
                    "address" => sprintf("Mae_%s_endereco", uniqid()),
                    "phone" => rand(9999990, 9999999),
                    "dateofbirth" => generateRandomDateTime(((date("Y") - (rand(20, 90)))), date("Y"))
                ]
            );
            $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
            $output = $createdMotherUseCase->execute($mother);
            $this->assertEquals(true, $output);
            $mothers[] = $mother;
        }


        $newborn = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Bebê_%s", uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mothers[rand(1, count($mothers))]->code,

            ]
        );
        $createdNewBornUseCase =  new CreatedNewBornUseCase($newbornMemory);
        $output = $createdNewBornUseCase->execute($newborn);
        $this->assertEquals(true, $output);

       
    }
    function testFailHasnewbornRegistred()
    {
        $this->expectException(NewbornAlreadyExistsException::class);
        $newbornMemory = new NewBornInMemoryRepository();
        $motherMemory = new MotherInMemoryRepository();
        $mothers = [];
        $newbornBirth = date("Y") - 30;
        for ($next = 0; $next < 2000; $next++) {
            $mother  = new MotherInput(
                [
                    "code" => uniqid(),
                    "name" => sprintf("Mae_%s", uniqid()),
                    "address" => sprintf("Mae_%s_endereco", uniqid()),
                    "phone" => rand(9999990, 9999999),
                    "dateofbirth" => generateRandomDateTime(((date("Y") - (rand(20, 90)))), date("Y"))
                ]
            );
            $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
            $output = $createdMotherUseCase->execute($mother);
            $this->assertEquals(true, $output);
            $mothers[] = $mother;
        }


        $newborn = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Bebê_%s", uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mothers[rand(1, count($mothers))]->code,

            ]
        );
        $createdNewBornUseCase =  new CreatedNewBornUseCase($newbornMemory);
        $createdNewBornUseCase->execute($newborn);
        $createdNewBornUseCase =  new CreatedNewBornUseCase($newbornMemory);
        $createdNewBornUseCase->execute($newborn);
    }


    function testUpdatenewborn()
    {


        $newbornMemory = new NewBornInMemoryRepository();
        $motherMemory = new MotherInMemoryRepository();
        $mothers = [];
        $newbornBirth = date("Y") - 30;
        for ($next = 0; $next < 2000; $next++) {
            $mother  = new MotherInput(
                [
                    "code" => uniqid(),
                    "name" => sprintf("Mae_%s", uniqid()),
                    "address" => sprintf("Mae_%s_endereco", uniqid()),
                    "phone" => rand(9999990, 9999999),
                    "dateofbirth" => generateRandomDateTime(((date("Y") - (rand(20, 90)))), date("Y"))
                ]
            );
            $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
            $output = $createdMotherUseCase->execute($mother);
            $this->assertEquals(true, $output);
            $mothers[] = $mother;
        }


        $newborn = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Bebê_%s", uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mothers[rand(1, count($mothers))]->code,

            ]
        );
        $createdNewBornUseCase =  new CreatedNewBornUseCase($newbornMemory);
        $output = $createdNewBornUseCase->execute($newborn);
        $this->assertEquals(true, $output);

        $newborn->birthweight =rand(1, 15);
        $newborn->height =rand(1, 60);
       
        $updatenewbornUseCase = new UpdateNewbornUseCase($newbornMemory);
        $output = $updatenewbornUseCase->execute($newborn->code, $newborn);
        $this->assertEquals(true, $output);
    }
}
