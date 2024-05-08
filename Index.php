<!DOCTYPE html>
<html>
<head>
    <title>Calculadora</title>
</head>
<body>
    <form action="" method="POST">
        <label for="n1">Número 1:</label> 
        <input type="number" name="n1" id="n1">

        <label for="operacao"></label> 
        <select name="operacao" id="operacao">
            <option value="1">Soma(+)</option>
            <option value="2">Subtração(-)</option>
            <option value="3">Multiplicação(*)</option>
            <option value="4">Divisão(/)</option>
            <option value="5">Fatoração(!)</option>
            <option value="6">Potenciação(^)</option>
        </select>

        <label for="n2">Número 2:</label> 
        <input type="number" name="n2" id="n2">

        <button type="submit">Calcular</button>
        <button type="submit" name="limpar_historico">Limpar Histórico</button>
        <button type="submit" name="memoria">Memória (M)</button>
        <p>Clique duas vezes para excluir o histórico</p>
    </form>

    <?php
session_start();

// Verifica se o histórico já existe na sessão
if (!isset($_SESSION['historico'])) {
    $_SESSION['historico'] = [];
}

// Função para adicionar uma entrada ao histórico
function adicionarHistorico($n1, $n2, $operacao, $resultado) {
    $_SESSION['historico'][] = array('n1' => $n1, 'n2' => $n2, 'operacao' => $operacao, 'resultado' => $resultado);
}

// Função para adicionar um valor à memória
function adicionarMemoria($n1, $n2, $operacao) {
    $_SESSION['memoria'] = array('n1' => $n1, 'n2' => $n2, 'operacao' => $operacao);
}

// Função para trazer o valor da memória para o visor
function exibirMemoria() {
    if (isset($_SESSION['memoria'])) {
        $memoria = $_SESSION['memoria'];
        if (isset($memoria['n1']) && isset($memoria['n2'])) {
            echo "Número 1: " . $memoria['n1'] . ", Operação: " . operacaoTexto($memoria['operacao']) . ", Número 2: " . $memoria['n2'];
        } else {
            echo "Memória vazia";
        }
    } else {
        echo "Memória vazia";
    }
}

// Verifica se o botão de memória foi pressionado
if (isset($_POST['memoria'])) {
    $n1 = isset($_POST['n1']) ? $_POST['n1'] : '';
    $n2 = isset($_POST['n2']) ? $_POST['n2'] : '';
    $operacao = isset($_POST['operacao']) ? $_POST['operacao'] : '';
    adicionarMemoria($n1, $n2, $operacao);
}

// Verifica se os dados do formulário foram enviados
if (isset($_POST['n1']) && isset($_POST['n2']) && isset($_POST['operacao'])) {
    // Obtém os valores dos campos do formulário
    $n1 = $_POST['n1'];
    $n2 = $_POST['n2'];
    $opcao = $_POST['operacao'];

    // Funções para as operações matemáticas
    if ($n1 !== '' && $n2 !== '') {
        function soma($n1, $n2) {
            return $n1 + $n2;
        }

        function menos($n1, $n2) {
            return $n1 - $n2;
        }

        function mult($n1, $n2) {
            return $n1 * $n2;
        }

        function divd($n1, $n2) {
            if ($n2 == 0) {
                return "Não é possível dividir por zero";
            } else {
                return $n1 / $n2;
            }
        }

        function potencia($n1, $n2){
            return pow($n1, $n2);
        }

        function fatoracao($n) {
            if ($n == 0) {
                return 1;
            } else {
                return $n * fatoracao($n - 1);
            }
        }

        // Executa a operação selecionada
        switch ($opcao) {
            case 1:
                $resultado = soma($n1, $n2);
                break;
            case 2:
                $resultado = menos($n1, $n2);
                break;
            case 3:
                $resultado = mult($n1, $n2);
                break;
            case 4:
                $resultado = divd($n1, $n2);
                break;
            case 6:
                $resultado = potencia($n1, $n2);
                break;
            case 5:
                $resultado = fatoracao($n1);
                break;
            default:
                $resultado = "Operação inválida";
        }

        // Adiciona a operação ao histórico
        adicionarHistorico($n1, $n2, $opcao, $resultado);

        // Exibe o resultado
        echo "Resultado: " . $resultado;
    } else {
        echo "Insira valores para Número 1 e Número 2.";
    }
} else {
    // Se os dados do formulário não foram enviados, exibe uma mensagem de erro
    echo "";
}

// Exibe o histórico
echo "<h2>Histórico de Contas:</h2>";
echo "<ul>";
foreach ($_SESSION['historico'] as $conta) {
    echo "<li>" . $conta['n1'] . " " . operacaoTexto($conta['operacao']) . " " . $conta['n2'] . " = " . $conta['resultado'] . "</li>";
}
echo "</ul>";

// Função para obter o texto da operação
function operacaoTexto($opcao) {
    switch ($opcao) {
        case 1:
            return "+";
        case 2:
            return "-";
        case 3:
            return "*";
        case 4:
            return "/";
        case 6:
            return "^";
        case 5:
            return "!";
        default:
            return "Operação inválida";
    }
}

if (isset($_POST['limpar_historico'])) {
    $_SESSION['historico'] = [];
}

echo "<li>Memória: ";
        exibirMemoria();
        echo "</li>";

?>
</body>
</html>

