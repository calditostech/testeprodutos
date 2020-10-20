<?php
/**
 * Tabela de Produtos
 *
 * Ricardo Ferreira Moreira
 */
 
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $produto = (isset($_POST["produto"]) && $_POST["produto"] != null) ? $_POST["produto"] : "";
    $preco = (isset($_POST["preco"]) && $_POST["preco"] != null) ? $_POST["preco"] : "";
    $cor = (isset($_POST["cor"]) && $_POST["cor"] != null) ? $_POST["cor"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
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
            $stmt = $conexao->prepare("UPDATE produtos SET produto=?, preco=?, cor=? WHERE id = ?");
            $stmt->bindParam(4, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO produtos (produto, preco, cor) VALUES (?, ?, ?)");
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
            $id = $rs->id;
            $produto = $rs->produto;
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
        $stmt = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
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
        </head>
        <body>
            <form action="?act=save" method="POST" name="form1" >
                <h1>Tabela de Produtos</h1>
                <hr>
                <input type="hidden" name="id" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                Produto:
               <input type="text" name="nome" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($produto) && $produto != null || $produto != "") {
                   echo "value=\"{$produto}\"";
               }
               ?> />
               Preço:
               <input type="text" name="preco" <?php
 
               // Preenche o email no campo email com um valor "value"
               if (isset($preco) && $preco != null || $preco != "") {
                   echo "value=\"{$preco}\"";
               }
               ?> />
               Cor:
               <input type="text" name="cor" <?php
 
               // Preenche o celular no campo celular com um valor "value"
               if (isset($cor) && $cor != null || $cor != "") {
                   echo "value=\"{$cor}\"";
               }
               ?> />
               <input type="submit" value="salvar" />
               <input type="reset" value="Novo" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Produtos</th>
                    <th>Preços</th>
                    <th>Cor</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM produtos");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->produto."</td><td>".$rs->preco."</td><td>".$rs->cor
                                       ."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
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