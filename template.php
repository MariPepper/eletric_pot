<?php

// ===============================
// LISTA DE INGREDIENTES
// ===============================
$ingredientes = [
    'azeite'     => '2 colheres de sopa',
    'cebola'     => '1 pequena picada',
    'alho'       => '2 dentes picados',
    'cogumelos'  => '200g fatiados',
    'arroz'      => '1 chávena',
    'agua'       => '2 chávenas',
    'sal'        => 'q.b.',
    'pimenta'    => 'q.b.',
    'salsa'      => 'a gosto'
];

$panela = 'panela média';
$fogo = 'médio';
$tempoCozedura = 15;

// ===============================
// PASSOS DA RECEITA
// ===============================
$passos = [
    [
        'acao' => 'aquecer',
        'ingredientes' => ['azeite'],
        'prefixo' => '',
        'extra' => "na $panela em fogo $fogo"
    ],
    [
        'acao' => 'refogar',
        'ingredientes' => ['cebola', 'alho'],
        'prefixo' => '',
        'extra' => ''
    ],
    [
        'acao' => 'adicionar',
        'ingredientes' => ['cogumelos'],
        'prefixo' => '',
        'extra' => 'e cozinhar até dourar'
    ],
    [
        'acao' => 'juntar',
        'ingredientes' => ['arroz'],
        'prefixo' => '',
        'extra' => 'e envolver bem'
    ],
    [
        'acao' => 'adicionar',
        'ingredientes' => ['agua', 'sal', 'pimenta'],
        'prefixo' => '',
        'extra' => ''
    ],
    [
        'acao' => 'cozinhar',
        'ingredientes' => [],
        'prefixo' => '',
        'extra' => "por $tempoCozedura minutos"
    ],
    [
        'acao' => 'finalizar',
        'ingredientes' => ['salsa'],
        'prefixo' => 'com',
        'extra' => ''
    ]
];

// ===============================
// ARTIGOS DEFINIDOS (o, a, os, as)
// ===============================
function artigo($palavra) {
    $genero = [
        'azeite'    => 'o',
        'cebola'    => 'a',
        'alho'      => 'o',
        'cogumelos' => 'os',
        'arroz'     => 'o',
        'agua'      => 'a',
        'sal'       => 'o',
        'pimenta'   => 'a',
        'salsa'     => 'a'
    ];

    return $genero[$palavra] ?? '';
}

// ===============================
// TÉCNICAS DE COZINHA
// ===============================
function tecnica($acao) {
    $tecnicas = [
        'aquecer'  => 'Aquecer',
        'refogar'  => 'Refogar',
        'adicionar'=> 'Adicionar',
        'juntar'   => 'Juntar',
        'cozinhar' => 'Cozinhar',
        'finalizar'=> 'Finalizar'
    ];

    return $tecnicas[$acao] ?? ucfirst($acao);
}

// ===============================
// FORMATAR INGREDIENTE
// ===============================
function formatarIngrediente($chave, $ingredientes) {
    return artigo($chave) . " {$chave} ({$ingredientes[$chave]})";
}

// ===============================
// FORMATAR LISTA DE INGREDIENTES
// ===============================
function formatarListaIngredientes(array $lista) {

    $total = count($lista);

    if ($total === 0) {
        return '';
    }

    if ($total === 1) {
        return $lista[0];
    }

    if ($total === 2) {
        return $lista[0] . " e " . $lista[1];
    }

    return implode(', ', array_slice($lista, 0, -1)) . " e " . $lista[$total - 1];
}

// ===============================
// ADICIONAR PASSO
// ===============================
function adicionar($texto) {
    echo "➡️ $texto\n";
}

// ===============================
// RECEITA DINÂMICA
// ===============================
function executarReceita($passos, $ingredientes) {

    foreach ($passos as $passo) {

        $tecnica = tecnica($passo['acao']);

        // Ingredientes formatados
        $listaIngredientes = array_map(function($ing) use ($ingredientes) {
            return formatarIngrediente($ing, $ingredientes);
        }, $passo['ingredientes']);

        // Evitar warnings quando não há ingredientes
        $textoIngredientes = formatarListaIngredientes($listaIngredientes);

        // Construção FINAL da frase
        $frase = $tecnica;

        if ($textoIngredientes !== '') {
            if (!empty($passo['prefixo'])) {
                $frase .= " {$passo['prefixo']} $textoIngredientes";
            } else {
                $frase .= " $textoIngredientes";
            }
        }

        if (!empty($passo['extra'])) {
            $frase .= " {$passo['extra']}";
        }

        adicionar($frase);
    }
}

// ===============================
// EXECUTAR RECEITA
// ===============================
executarReceita($passos, $ingredientes);

?>
