<?php
/**
 * Tabela de Produtos
 *
 * Ricardo Ferreira Moreira
 */
 
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["idprod"]) && $_POST["idprod"] != null) ? $_POST["idprod"] : "";
    $produto = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $preco = (isset($_POST["preco"]) && $_POST["preco"] != null) ? $_POST["preco"] : "";
    $cor = (isset($_POST["cor"]) && $_POST["cor"] != null) ? $_POST["cor"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["idprod"]) && $_GET["idprod"] != null) ? $_GET["idprod"] : "";
    $produto = NULL;
    $preco = NULL;
    $cor = NULL;
}
 
// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=localhost; dbname=testeprodutos", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $produto != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE produtos SET idprod=?, preco=?, cor=? WHERE idprod = ?");
            $stmt->bindParam(4, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO produtos (preco, nome , cor) VALUES (?, ?, ?)");
        }
        $stmt->bindParam(1, $produto);
        $stmt->bindParam(2, $preco);
        $stmt->bindParam(3, $cor);
 
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $produto = null;
                $preco = null;
                $cor = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->idprod;
            $produto = $rs->nome;
            $preco = $rs->preco;
            $cor = $rs->cor;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM produtos WHERE idprod = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Tabela de Produtos</title>
            <link href="css/styles.css" rel="stylesheet"/>
            <script src="js/seu-script.js"></script>
        </head>
        <body>
            <h2 class="titulo-index"> Tabela de Produtos </h2>

            

            <a href="#abrirModal"><button style="background-color: green; color: white;"><b>INSERE REGISTROS</b></button></a>

        <div id="abrirModal" class="modal">
          <a href="#fechar" title="Fechar" class="fechar">x</a>
           <h2 style="color: white;">Insira os registros</h2>
           <label style="color: white;">PREÇO:</label>
           <input type="text" name="preco" placeholder="Digite o preço" required>
           <label style="color: white;">PRODUTO:</label>
           <input type="text"   name="nome" placeholder="Digite o nome do produto" required>
           <label style="color: white;">COR:</label>
         <select name="select">
           <option value="valor1">Amarelo</option> 
           <option value="valor2" selected>Azul</option>
           <option value="valor3">Vermelho</option>
         </select>
         <button type="submit" style="background-color: blue; color: white;">Enviar</button>
        </div>
            <table class="tabela-prod">
                <tr>
                    <th>Produtos</th>
                    <th>Preços</th>
                    <th>Cor</th>
                    <th>Açoes</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT produtos.idprod, produtos.nome, produtos.cor, preco.idpreco, preco.preco FROM produtos, preco WHERE produtos.idprod = preco.idpreco");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->idprod."</td><td>".$rs->preco."</td><td>".$rs->cor
                                       ."</td><td><center><button href=\"?act=upd&id=".$rs->idprod."\">Alterar</button>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<button href=\"?act=del&id=".$rs->idprod."\">Excluir</button></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
        </body>
    </html>