<?php

use Application\MotherInput;
use PHPUnit\Framework\TestCase;
use Domain\UseCases\UpdateMotherUseCase;
use Domain\UseCases\CreatedMotherUseCase;
use Infra\Repositories\IMotherRepository;
use Infra\Repositories\Entities\MotherEntity;
use Infra\Exceptions\motherAlreadyExistsException;

 


class MotherInMemoryRepository implements IMotherRepository{

    private $tablemothers=[];
    function created(MotherInput $mother){
        if(!is_null($this->byCode($mother->code))){
            throw new motherAlreadyExistsException("duplicate record");
        }
        $motherEntity =  new MotherEntity($mother->toArray());
        $this->tablemothers[]=$motherEntity;
        return true;
    }
    function update($code,MotherInput $mother){
        foreach($this->tablemothers as $key => $row){
            if($this->byCode($code)){
                $motherEntity =  new MotherEntity($mother->toArray());
                $this->tablemothers[$key] = $motherEntity;
                return true;
            }
        }
        
        return false;
    }
    
    function byCode($code){
        foreach($this->tablemothers as $key => $row){
            if($row->code==$code){
                return new MotherInput($row->toArray());
            }
        }
        return null;
    }
};


class MotherTest extends TestCase{
    function testCreatedMother(){
        $motherMemory=new MotherInMemoryRepository();

        $mother = new MotherInput(
            [
                "code"=>uniqid(),
                "name"=>sprintf("Mae_%s",uniqid()),
                "address"=>sprintf("Mae_%s_endereco",uniqid()),
                "phone" => rand(9999990,9999999),
                "dateofbirth"=>1983
            ]
        );
        $createdMotherUseCase =  new CreatedMotherUseCase($motherMemory);
        $output=$createdMotherUseCase->execute($mother);
        $this->assertEquals(true,$output);
    }


    function testUpdateMother(){
        $motherMemory=new MotherInMemoryRepository();
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
    

    function testFailHasMotherRegistred(){
        $this->expectException(motherAlreadyExistsException::class);
        $motherMemory=new MotherInMemoryRepository();
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
        $createdMotherUseCase =  new CreatedMotherUseCase( $motherMemory);
        $output=$createdMotherUseCase->execute($mother);



    }
    


}