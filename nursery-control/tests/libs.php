<?php



function dd($obj){
    print_r(["<pre>",$obj,"<pre>"]);die;
}

function generateRandomDateTime($startYear = 2000, $endYear = 2030) {
    // Generate random timestamps between January 1st of the start year and December 31st of the end year
    $startTimestamp = strtotime("{$startYear}-01-01 00:00:00");
    $endTimestamp = strtotime("{$endYear}-12-31 23:59:59");

    // Generate a random timestamp within the range
    $randomTimestamp = rand($startTimestamp, $endTimestamp);

    // Format the date as: dd/mm/yyyy hh:mm:ss
    return date("d/m/Y H:i:s", $randomTimestamp);
}


 

function gerarTextoPartoAleatorio() {
    $nomesBebes = ["Miguel", "Alice", "Arthur", "Helena", "Gael", "Laura", "Theo", "Valentina", "Davi", "Sophia"];
    $tiposParto = ["parto normal", "cesariana", "parto humanizado", "parto na água", "parto domiciliar"];
    $horarios = ["de manhã", "à tarde", "à noite", "de madrugada"];
    $locais = ["no hospital central", "na maternidade São Lucas", "no hospital municipal", "em casa", "na clínica Vida"];
    $condicoes = ["sem complicações", "com pequeno atraso", "com acompanhamento da doula", "com uso de anestesia", "com suporte da equipe médica"];
    $peso = rand(2500, 4000) / 1000; // entre 2.5kg e 4kg
    $altura = rand(45, 55); // entre 45 e 55 cm

    $nome = $nomesBebes[array_rand($nomesBebes)];
    $parto = $tiposParto[array_rand($tiposParto)];
    $horario = $horarios[array_rand($horarios)];
    $local = $locais[array_rand($locais)];
    $condicao = $condicoes[array_rand($condicoes)];

    return "O bebê $nome nasceu $horario $local, através de $parto, $condicao. Ele(a) nasceu com $peso kg e $altura cm de comprimento.";
}

 


function formatarDataHora($dataHora) {
    $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dataHora);
    if ($dateTime) {
        return $dateTime->format('Ymd His');
    } else {
        return "Data inválida";
    }
}

