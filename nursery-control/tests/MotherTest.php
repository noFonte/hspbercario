<?php
 
use Application\MotherInput;
use PHPUnit\Framework\TestCase;
use Domain\UseCases\UpdateMotherUseCase;
use Domain\UseCases\CreatedMotherUseCase;
use Infra\Repositories\IMotherRepository;
use Infra\Repositories\Entities\MontherEntity;









class MotherTest extends TestCase{
    function testCreatedMother(){



       
        $mother = new MotherInput(
            [
                "code"=>uniqid(),
                "name"=>sprintf("Mae_%s",uniqid()),
                "address"=>sprintf("Mae_%s_endereco",uniqid()),
                "phone" => rand(9999990,9999999),
                "dateofbirth"=>1983
            ]
        );



      
        $createdMotherUseCase =  new CreatedMotherUseCase(new MotherRepository());
        $output=$createdMotherUseCase->execute($mother);
        $this->assertEquals(true,$output);
    }


    function testUpdateMother(){
       
        $mother = new MotherInput(
            [
                "code"=>uniqid(),
                "name"=>sprintf("Mae_%s",uniqid()),
                "address"=>sprintf("Mae_%s_endereco",uniqid()),
                "phone" => rand(9999990,9999999),
                "dateofbirth"=>1983
            ]
        );


        $createdMotherUseCase =  new CreatedMotherUseCase(new MotherRepository());
        $output=$createdMotherUseCase->execute($mother);
        $this->assertEquals(true,$output);


        $mother->dateofbirth = 1900;
        $updateMotherUseCase =new UpdateMotherUseCase(new MotherRepository());
        $output=$updateMotherUseCase->execute($mother->code,$mother);
        $this->assertEquals(true,$output);
        


    }
    

}