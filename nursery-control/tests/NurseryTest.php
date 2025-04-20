<?php

use Application\NewBornInput;
use PHPUnit\Framework\TestCase;

require_once("libs.php");






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




interface INurseryRepository {
    function created(NurseryInput $nursery);
}

class NurseryInMemoryRepository implements INurseryRepository {

    private $tableNurserys=[];

    function created(NurseryInput $nursery){
        $this->tableNurserys[]=new NurseryEntity($nursery->toArray());
        return true;

    }
}

class  CreatedNurseryUseCase
{

    private INurseryRepository $iNurseryRepository;

    public function __construct(INurseryRepository $iNurseryRepository)
    {
        $this->iNurseryRepository =  $iNurseryRepository;
    }
    public function execute(NurseryInput $nursery) {

        return $this->iNurseryRepository->created($nursery);
         
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


    }
}
