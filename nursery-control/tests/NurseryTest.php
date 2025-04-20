<?php

use Application\DoctorInput;
use Application\MotherInput;
use Application\NewBornInput;
use PHPUnit\Framework\TestCase;
use Domain\UseCases\CreatedDoctorUseCase;
use Domain\UseCases\CreatedMotherUseCase;
use Domain\UseCases\CreatedNewBornUseCase;
use Infra\Repositories\IDoctorRepository;
use Infra\Repositories\IMotherRepository;
use Infra\Repositories\INewBornInRepository;

require_once("libs.php");

require_once("RepositoriesInMemory/DoctorInMemoryRepository.php");
require_once("RepositoriesInMemory/NewBornInMemoryRepository.php");
require_once("RepositoriesInMemory/MotherInMemoryRepository.php");








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





class NewBirthInput
{
    private $inputs = ["code","dateofbirth", "associatedwithMother", "associatedwithDoctor"];
    public $code;
    public $dateofbirth;
    public $associatedwithMother;
    public $associatedwithDoctor;
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




interface IChildbirthRepository{
    function created(NewBirthInput $newBirth);
}


class ChildbirthInMemoryRepository  implements IChildbirthRepository{

    private $tableChildbirth = [];
    function created(NewBirthInput $newBirth){
        
        $this->tableChildbirth[] = new ChildbirthEntity($newBirth->toArray());
        return true;
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




class RegisteringANewBirthUseCase
{
    private INurseryRepository $iNurseryRepository;
    private IMotherRepository $motherRepository;
    private INewBornInRepository $newBornRepository;
    private IDoctorRepository $iDoctorRepository;
    private IChildbirthRepository $iChildbirthRepository;

    public function __construct(
        INurseryRepository $iNurseryRepository,
        IMotherRepository $motherRepository,
        INewBornInRepository $newBornRepository,
        IDoctorRepository $iDoctorRepository,
        IChildbirthRepository $iChildbirthRepository
    ) {
        $this->motherRepository = $motherRepository;
        $this->newBornRepository = $newBornRepository;
        $this->iDoctorRepository = $iDoctorRepository;
        $this->iNurseryRepository = $iNurseryRepository;
        $this->iChildbirthRepository = $iChildbirthRepository;
    }
    public function execute(NewBirthInput $newBirth) {

        dd($newBirth);


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


    function testRegisteringANewBirth()
    {

        /*
            Deve Adicionar um Novo  parto, Associando A Mãe e a Criança e o
            MEdico que realizou o parto se o [**bercario estiver ativo]

        */

        $motherMemory = new MotherInMemoryRepository();
        $doctorMemory = new DoctorInMemoryRepository();
        $newbornMemory = new NewBornInMemoryRepository();
        $nurseryMemory = new NurseryInMemoryRepository();
        $childbirthMemory = new ChildbirthInMemoryRepository();
        

        $newbornBirth = date("Y") - 30;


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

      

        $newborn = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Bebê_%s", uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mother->code,

            ]
        );
        $createdNewBornUseCase =  new CreatedNewBornUseCase($newbornMemory);
        $output = $createdNewBornUseCase->execute($newborn);
        $this->assertEquals(true, $output);

       

        $newBirth = new NewBirthInput(
            [
                "code" => uniqid(),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "associatedwithMother" => $mother->code,
                "associatedwithDoctor" => $doctor->code,
            ]
        );
   

        $registeringANewBirth =  new RegisteringANewBirthUseCase(
            $nurseryMemory, $motherMemory, $newbornMemory, $doctorMemory,$childbirthMemory
        );
        $output = $registeringANewBirth->execute($newBirth);
        $this->assertEquals(true, $output);



        
    }
}
