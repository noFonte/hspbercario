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
    private $inputs = ["code", "dateofbirth", "associatedwithMother", "associatedwithDoctor", "associatedNursery"];
    public $code;
    public $dateofbirth;
    public $associatedwithMother;
    public $associatedwithDoctor;
    public $associatedNursery;

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



class ChildbirthEntity
{
    private $inputs = ["code", "associatedwithNursry", "birthDate", "birthDetails", "associatedChildbirth"];
    public $code;
    public $associatedwithNursry;
    public $birthDate;
    public $birthDetails;
    public $associatedChildbirth;
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






class ChildbirthOutPut
{
    private $inputs = ["code", "associatedwithNursry", "birthDate", "birthDetails", "mother", "doctor", "newborn", "nursry"];
    public $code;
    public $associatedwithNursry;
    public $birthDate;
    public $birthDetails;
    public $mother;
    public $doctor;
    public $newborn;
    public $nursry;

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








interface IChildbirthRepository
{
    function created(NewBirthInput $newBirth, NurseryInput $nurseryInput);
    function all();
}


class ChildbirthInMemoryRepository  implements IChildbirthRepository
{

    private $tableChildbirth = [];
    function created(NewBirthInput $newBirth, NurseryInput $nurseryInput)
    {

        $childbirthEntity = new ChildbirthEntity([
            "code" => uniqid(),
            "associatedwithNursry" => $newBirth->code,
            "associatedChildbirth" => $nurseryInput->code,
            "birthDate" => $newBirth->dateofbirth,
            "birthDetails" => gerarTextoPartoAleatorio()

        ]);
        $this->tableChildbirth[] = $childbirthEntity;
        return true;
    }


    function all()
    {
        $childbirths = [];
        foreach ($this->tableChildbirth as $row) {
            $childbirths[] = new ChildbirthOutPut($row->toArray());
        }

        return $childbirths;
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
    private IChildbirthRepository $iChildbirthRepository;

    public function __construct(
        INurseryRepository $iNurseryRepository,
        IChildbirthRepository $iChildbirthRepository
    ) {
        $this->iNurseryRepository = $iNurseryRepository;
        $this->iChildbirthRepository = $iChildbirthRepository;
    }
    public function execute(NewBirthInput $newBirth, NurseryInput $nurseryInput)
    {

        dd([$newBirth, $nurseryInput]);
        return $this->iChildbirthRepository->created($newBirth, $nurseryInput);
    }
}



class AllNewBirthUseCase
{
    private INurseryRepository $iNurseryRepository;
    private IMotherRepository $motherRepository;
    private INewBornInRepository $newBornRepository;
    private IDoctorRepository $iDoctorRepository;
    private IChildbirthRepository $iChildbirthRepository;

    public function __construct(
        INurseryRepository $iNurseryRepository,
        IChildbirthRepository $iChildbirthRepository,
        IDoctorRepository $iDoctorRepository,
        IMotherRepository $motherRepository,
        INewBornInRepository $newBornRepository

    ) {
        $this->iNurseryRepository = $iNurseryRepository;
        $this->iChildbirthRepository = $iChildbirthRepository;
        $this->iDoctorRepository = $iDoctorRepository;
        $this->motherRepository = $motherRepository;
        $this->newBornRepository = $newBornRepository;
    }
    public function execute($showsCompleteInformation = true)
    {

        if ($showsCompleteInformation) {
            return $this->iChildbirthRepository->all();
        } else {
            $iChildbirths = $this->iChildbirthRepository->all();
            foreach ($iChildbirths as $key => $row) {

                dd([$row,  $this->iNurseryRepository]);
            }
        }
    }
}


/*Inicio Teste */
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
        $createdNurseryUseCase->execute($Nursery);
        $createdNurseryUseCase =  new CreatedNurseryUseCase($nurseryMemory);
        $createdNurseryUseCase->execute($Nursery);
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
                "dateofbirth" => formatarDataHora(generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y"))),
                "associatedwithMother" => $mother->code,
                "associatedwithDoctor" => $doctor->code,
                "associatedNursery" => $nursery->code,
            ]
        );

       

        $registeringANewBirth =  new RegisteringANewBirthUseCase(
            $nurseryMemory,
            $childbirthMemory
        );
        $output = $registeringANewBirth->execute($newBirth, $nursery);
        $this->assertEquals(true, $output);
    }

    



    function testRegisteringANewBirthOfTwins()
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
        $newborns = [];
        $newborns[] = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("Bebê_%s", uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mother->code,

            ]
        );
        $newborns[] = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("(%s) - Bebê_%s", count($newborns) + 1, uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mother->code,

            ]
        );


        $newborns[] = new NewBornInput(
            [
                "code" => uniqid(),
                "name" => sprintf("(%s) - Bebê_%s", count($newborns) + 1, uniqid()),
                "dateofbirth" => generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y")),
                "birthweight" => rand(1, 15),
                "height" =>  rand(1, 60),
                "associatedwithMother" => $mother->code,

            ]
        );
        $createdNewBornUseCase =  new CreatedNewBornUseCase($newbornMemory);




        foreach ($newborns as $row) {
            $output = $createdNewBornUseCase->execute($row);
            $this->assertEquals(true, $output);
            $newBirth = new NewBirthInput(
                [
                    "code" => uniqid(),
                    "dateofbirth" => formatarDataHora(generateRandomDateTime(((date("Y") - $newbornBirth)), date("Y"))),
                    "associatedwithMother" => $mother->code,
                    "associatedwithDoctor" => $doctor->code,
                    "associatedNursery" => $nursery->code,
                ]
            );


            $registeringANewBirth =  new RegisteringANewBirthUseCase(
                $nurseryMemory,
                $childbirthMemory
            );
            $output = $registeringANewBirth->execute($newBirth, $nursery);
            $this->assertEquals(true, $output);
        }
        $allNewBirthUseCase =  new AllNewBirthUseCase(
            $nurseryMemory,
            $childbirthMemory,
            $doctorMemory,
            $motherMemory,
            $newbornMemory
        );
        $output = $allNewBirthUseCase->execute(false);


        $this->assertEquals(3, $output);
    }
}
