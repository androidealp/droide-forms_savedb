# droide-forms_savedb
Plugin para salvar os dados recebidos do formulário no banco, você consegue especificar os formulários que iram executar e customizar colunas na tabela destinada para isso, este recurso é muito util para relatório de recebimentos.

## Ações de edição

Existe dentro do plugin um recurso de edição para customização de recursos salvos.

Seletor de formulários, customizar mensagem de envio com sucesso no status, customizar mensagem de erro de envio para erro no status, existem 5 colunas na tabela para campos personalizados return_custom_1, return_custom_2, return_custom_3, return_custom_4, return_custom_5.

Você pode definir variaveis nos campos desta forma [[minha_variavel]], com isso o sistema irá analizar com a variavel universal $returnTrigger de tratamento do formulário e implementar no lugar da variavel o texto experado.

Isso serve para plugins que desejam manipular as informações customizadas salvas.

## Exemplo

```php

<?php 

    /***Exemplo de plugin que analiza o post antes de enviar para o tratamento do layout**/

     public function onDroideformsBeforePublisheLayout(&$module, &$layout, &$post, &$log, &$returnTrigger)
     {

         $idform = $module->get('id_form'); 
         if($idform == 'contato_id')
         {
             // aqui registro no array uma variavel que será adicionada no admin do plugin do savedb
             if($post['aceito_termo'] == 1)
             {
                 $returnTrigger['variavel_no_plugin'] = 'O usuário aceitou';
             }else{
                 $returnTrigger['variavel_no_plugin'] = 'O usuário reclinou';
             }
         }
     }


    /*****/

?>

```

## Detalhamento
Após fazer o plugin você pega a variável criada **variavel_no_plugin** no nosso custom plugin e adicionamos dentro do admin do plugin droide-forms_savedb desta forma:
```
[[variavel_no_plugin]]
```
O sistema irá validadar esta variavel tentando localizar nos plugins disparados primeiro se existe o recurso.

 **Obs: utilize triggers antes do trigger de pos envio, para ter certeza que o recurso desenvolvido pelo plugin será tratado primerio o onDroideformsPosSend e o onDroideformsPosSendError é utilizado pelo plugin de banco para registro**

 ## Registro de Log

 O sistema possui um simples registro de log chamado log/plugin_droidesavedb.php, este log registra se ocorrer algum erro na base de dados no processo de salvar.

 ### Versions

| Version | Link |
|---------|------|
| v 0.1   | [download - v0.1 - j3.8.x testado em droide-forms 1.2](https://github.com/androidealp/droide-forms_savedb/archive/v0.1.zip) |
