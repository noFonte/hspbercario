<?php

use Application\MotherInput;
use PHPUnit\Framework\TestCase;
use Domain\UseCases\UpdateMotherUseCase;
use Domain\UseCases\CreatedMotherUseCase;
use Infra\Exceptions\MotherAlreadyExistsException;
 
require_once("libs.php");
require_once("RepositoriesInMemory/MotherInMemoryRepository.php");



class MotherTest extends TestCase
{
    function testCreatedMother()
    {
        $motherMemory = new MotherInMemoryRepository();

        $mother = new MotherInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Mae_%s", uniqid()),
                "address" => sprintf("Mae_%s_endereco", uniqid()),
                "phone" => rand(9999990, 9999999),
                "dateofbirth" => 1983
            ]
        );
        $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
        $output = $createdMotherUseCase->execute($mother);
        $this->assertEquals(true, $output);
    }


    function testUpdateMother()
    {
        $motherMemory = new MotherInMemoryRepository();
        $mother = new MotherInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Mae_%s", uniqid()),
                "address" => sprintf("Mae_%s_endereco", uniqid()),
                "phone" => rand(9999990, 9999999),
                "dateofbirth" => 1983
            ]
        );
        $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
        $output = $createdMotherUseCase->execute($mother);
        $this->assertEquals(true, $output);
        $mother->dateofbirth = 1900;
        $updateMotherUseCase = new UpdateMotherUseCase($motherMemory);
        $output = $updateMotherUseCase->execute($mother->code, $mother);
        $this->assertEquals(true, $output);
    }


    function testFailHasMotherRegistred()
    {
        $this->expectException(MotherAlreadyExistsException::class);
        $motherMemory = new MotherInMemoryRepository();
        $mother = new MotherInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Mae_%s", uniqid()),
                "address" => sprintf("Mae_%s_endereco", uniqid()),
                "phone" => rand(9999990, 9999999),
                "dateofbirth" => 1983
            ]
        );
        $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
        $output = $createdMotherUseCase->execute($mother);
        $this->assertEquals(true, $output);
        $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
        $output = $createdMotherUseCase->execute($mother);
    }
}
