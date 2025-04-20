<?php

use Application\NewBornInput;
use PHPUnit\Framework\TestCase;

require_once("libs.php");




class NurseryAlreadyExistsException extends Exception {}

class NurseryNotAlreadyExistsException extends Exception {}


class NurseryInput
{
    private $inputs = ["code",  "name", "state"];
    public $code;
    public $name;
    public $state;
    public function __construct($input = null)
    {

        if (!is_null($input) && is_array($input)) {

            foreach ($this->inputs as $value) {
                if (isset($input[$value])) {
                    $this->$value = $input[$value];
                }
            }
        }
    }


    public function toArray()
    {

        $props = [];

        foreach ($this->inputs as $value) {
            $props[$value] = $this->$value;
        }
        return $props;
    }
}

class NurseryOutPut
{
    private $inputs = ["code",  "name", "state"];
    public $code;
    public $name;
    public $state;
    public function __construct($input = null)
    {

        if (!is_null($input) && is_array($input)) {

            foreach ($this->inputs as $value) {
                if (isset($input[$value])) {
                    $this->$value = $input[$value];
                }
            }
        }
    }


    public function toArray()
    {

        $props = [];

        foreach ($this->inputs as $value) {
            $props[$value] = $this->$value;
        }
        return $props;
    }
}







class NurseryEntity
{
    private $inputs = ["code",  "name", "state"];
    public $code;
    public $name;
    public $state;
    public function __construct($input = null)
    {

        if (!is_null($input) && is_array($input)) {

            foreach ($this->inputs as $value) {
                if (isset($input[$value])) {
                    $this->$value = $input[$value];
                }
            }
        }
    }


    public function toArray()
    {

        $props = [];

        foreach ($this->inputs as $value) {
            $props[$value] = $this->$value;
        }
        return $props;
    }
}




interface INurseryRepository
{
    function created(NurseryInput $nursery);
    function active($code);
    function desactive($code);
    function byCode($code);
}

class NurseryInMemoryRepository implements INurseryRepository
{

    private $tableNurserys = [];

    function created(NurseryInput $nursery)
    {
        $isNursery = $this->byCode($nursery->code);
        if (!is_null($isNursery)) throw  new  NurseryAlreadyExistsException("duplicate record.");
        $this->tableNurserys[] = new NurseryEntity($nursery->toArray());
        return true;
    }
    function active($code)
    {
        $nursery = $this->byCode($code);
        if (is_null($nursery)) throw  new  NurseryNotAlreadyExistsException("nursery not exists.");
        foreach ($this->tableNurserys as $key => $row) {
            if ($row->code == $nursery->code && $row->state == true) {
                $this->tableNurserys[$key]->state = false;
                return true;
            }
        }
    }
    function desactive($code)
    {

        $nursery = $this->byCode($code);
        if (is_null($nursery)) throw  new  NurseryNotAlreadyExistsException("nursery not exists.");
        foreach ($this->tableNurserys as $key => $row) {
            if ($row->code == $nursery->code && $row->state == false) {
                $this->tableNurserys[$key]->state = true;
                return true;
            }
        }
    }
    function byCode($code)
    {
        foreach ($this->tableNurserys as $key => $row) {
            if ($row->code == $code) {
                return new NurseryOutPut($row->toArray());
            }
        }
        return null;
    }
}

class  CreatedNurseryUseCase
{

    private INurseryRepository $iNurseryRepository;

    public function __construct(INurseryRepository $iNurseryRepository)
    {
        $this->iNurseryRepository =  $iNurseryRepository;
    }
    public function execute(NurseryInput $nursery)
    {

        return $this->iNurseryRepository->created($nursery);
    }
}





class ActiveOrDesactiveNurseryUseCase
{
    private INurseryRepository $iNurseryRepository;

    public function __construct(INurseryRepository $iNurseryRepository)
    {
        $this->iNurseryRepository =  $iNurseryRepository;
    }
    public function execute($code, $state)
    {
        if ($state) {
            return $this->iNurseryRepository->desactive($code);
        } else {
            return $this->iNurseryRepository->active($code);
        }
    }
}

class NurseryTest extends TestCase
{
    function testCreatedNursery()
    {

        /*
        Criar um novo berçário se não existir, com os campos 
        [code],[name],[state], o nome deve ser Unico como código
        */


        $nurseryMemory = new NurseryInMemoryRepository();
        $Nursery = new NurseryInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Berçario_%s", uniqid()),
                "state" => true
            ]
        );
        $createdNurseryUseCase =  new CreatedNurseryUseCase($nurseryMemory);
        $output = $createdNurseryUseCase->execute($Nursery);
        $this->assertEquals(true, $output);
    }


    function testActivateOrDeactivateNursery()
    {

        /*
         Deve permitir Ativar e Desativar o Berçario , Deve Desativar o
         BErçario e não Deletar. pois tem Historicos
        */


        $nurseryMemory = new NurseryInMemoryRepository();
        $nursery = new NurseryInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Berçario_%s", uniqid()),
                "state" => true
            ]
        );
        $createdNurseryUseCase =  new CreatedNurseryUseCase($nurseryMemory);
        $output = $createdNurseryUseCase->execute($nursery);
        $this->assertEquals(true, $output);


        $activeOrDesactiveNurseryUseCase =  new ActiveOrDesactiveNurseryUseCase($nurseryMemory);
        $output = $activeOrDesactiveNurseryUseCase->execute($nursery->code, false);
        $this->assertEquals(true, $output);


        $activeOrDesactiveNurseryUseCase =  new ActiveOrDesactiveNurseryUseCase($nurseryMemory);
        $output = $activeOrDesactiveNurseryUseCase->execute($nursery->code, true);
        $this->assertEquals(true, $output);
    }


    function testFailHasNurseryRegistred()
    {
        $this->expectException(NurseryAlreadyExistsException::class);

        $nurseryMemory = new NurseryInMemoryRepository();
        $Nursery = new NurseryInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Berçario_%s", uniqid()),
                "state" => true
            ]
        );
        $createdNurseryUseCase =  new CreatedNurseryUseCase($nurseryMemory);
        $output = $createdNurseryUseCase->execute($Nursery);
        $createdNurseryUseCase =  new CreatedNurseryUseCase($nurseryMemory);
        $output = $createdNurseryUseCase->execute($Nursery);
    }
}
