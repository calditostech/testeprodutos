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
               if (isset($idprod) && $idprod != null || $idprod != "") {
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