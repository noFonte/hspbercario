<?php

use Application\MotherInput;
use PHPUnit\Framework\TestCase;

use Domain\UseCases\UpdateMotherUseCase;
use Domain\UseCases\CreatedMotherUseCase;
use Infra\Repositories\Entities\IMotherRepository;
 


class MotherInMomeryRepository implements IMotherRepository{

    private $tablemothers=[];


    function created(MotherInput $mother){
        $this->tablemothers[]=$mother;
        return true;
    }



    function update($code,MotherInput $mother){
        foreach($this->tablemothers as $key => $row){
            if($row->code==$code){
                $mother->code=$code;
                $this->tablemothers[$key] = $mother;
                return true;
            }
        }
        
        return false;
    }
    
};




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
        $createdMotherUseCase =  new CreatedMotherUseCase(new MotherInMomeryRepository());
        $output=$createdMotherUseCase->execute($mother);
        $this->assertEquals(true,$output);
    }


    function testUpdateMother(){
        $motherMemory=new MotherInMomeryRepository();
        $mother = new MotherInput(
            [
                "code"=>uniqid(),
                "name"=>sprintf("Mae_%s",uniqid()),
                "address"=>sprintf("Mae_%s_endereco",uniqid()),
                "phone" => rand(9999990,9999999),
                "dateofbirth"=>1983
            ]
        );
        $createdMotherUseCase =  new CreatedMotherUseCase( $motherMemory);
        $output=$createdMotherUseCase->execute($mother);
        $this->assertEquals(true,$output);
        $mother->dateofbirth = 1900;
        $updateMotherUseCase =new UpdateMotherUseCase($motherMemory);
        $output=$updateMotherUseCase->execute($mother->code,$mother);
        $this->assertEquals(true,$output);

    }
    

}