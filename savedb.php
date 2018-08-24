<?php
/**
 * @package     mod_droideforms.Plugin
 * @subpackage  droide-froms_savedb
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author 		André Luiz Pereira <[<and4563@gmail.com>]>
 */

defined('_JEXEC') or die ();

class plgDroideformsSavedb extends JPlugin{


  public function __construct(&$subject, $config)
  {

    parent::__construct($subject, $config);

  }

  /**
  * Organiza os elementos registrados no admin de forma amigavel para tratamento
  * @param  array $parans_bruto - array com os atributos vindos do repeteable
  * @return array array bruto tratado
  * @author André Luiz Pereira <andre@next4.com.br>
  */
  private function organizeArray($parans_bruto)
  {
    
    $total = count($parans_bruto['module_id']);

    $resultado = [];

    for ($i=0; $i <$total; $i++)
    { 
      $resultado[] = [
        'module_id'=>$parans_bruto['module_id'][$i],
        'custom_save'=>$parans_bruto['custom_save'][$i],
        'comando'=>$parans_bruto['comando'][$i],
      ];
    }

    return $resultado;
  }

  /**
  * Trigger que é disparada quando o evento de envio é bem succedido
  * @param  array $module - Objeto de module
  * @param  array $post - Array com os dados vindos do formulário
  * @param  string $sucesso - mensagem atual de sucesso
  * @param  string $log - para testes de erros
  * @param  array $returnTrigger - Recupera valores vindos de outros disparos
  * @author André Luiz Pereira <andre@next4.com.br>
  */
  public function onDroideformsPosSend(&$module,  &$post, &$sucesso, &$log, &$returnTrigger)
  {

    $params_bruto = json_decode($this->params->get('addtable',0),true);

    $parans = [];

    $returnTrigger['resultado'] = 'CPF em análise';

    if(in_array($module->id,$params_bruto['module_id']))
    {

      $parans = $this->organizeArray($params_bruto);

      $query_array = $this->Query($parans,$module,$post, $returnTrigger,'status_success');  

      $return = $this->execBD($query_array);


    }

  }

  /**
  * Trigger que é disparada quando o evento de envio é mal succedido
  * @param  object $module - Objeto de module de formulario
  * @param  array $post - Array com os dados vindos do formulário
  * @param  string $error - mensagem atual derror
  * @param  string $log - para testes de erros
  * @param  array $returnTrigger - Recupera valores vindos de outros disparos
  * @author André Luiz Pereira <andre@next4.com.br>
  */
  public function onDroideformsPosSendError(&$module,  &$post, &$error, &$log, &$returnTrigger)
  {

      $params_bruto = json_decode($this->params->get('addtable',0),true);

      $parans = [];
      $query = [];
      if(in_array($module->id,$params_bruto['module_id']))
      {
          $parans = $this->organizeArray($params_bruto);

          $query_array = $this->Query($parans,$module,$post, $returnTrigger, 'status_error');

          $return = $this->execBD($query_array);


      }


  }

   /**
    * Responsavel por montar a consulta antes de salvar no banco
    * @param  array $itens - itens previamente tratados, que serão enviados no banco
    * @param  object $module - module de formulario
    * @param  array $post - Array com os dados vindos do formulário
    * @param  array $returnTrigger - Recupera valores vindos de outros disparos
    * @param  string $type_send - tipo de envio succedido ou mal sucedido
    * @return array colunas tratadas
    * @author André Luiz Pereira <andre@next4.com.br>
  */
  private function Query($itens,$module,$post, $returnTrigger,$type_send)
  {
    // insert into #__droide_forms_register (form_id,form_name,dt_send,fileds, mensagem) values(%s,%s,%s,%s,%s);

    $coluns_custom = ['return_custom_1','return_custom_2','return_custom_3','return_custom_4','return_custom_5'];

    $insert_column = [
      'form_id'=>$module->get('id_form'),
      'form_name'=>$module->title,
      'dt_send'=>date('Y-m-d H:i:s'),
      'fileds'=>json_encode($post),
    ];

      foreach ($itens as $k => $field)
      {
        if($field['module_id'] == $module->id)
        {
          if($field['custom_save'] == $type_send)
          {
            $insert_column['status_custom'] = $this->checkVar($field['comando'],$returnTrigger);

          }elseif(in_array($field['custom_save'],$coluns_custom)){

            $insert_column[$field['custom_save']] = $this->checkVar($field['comando'],$returnTrigger);

          } //lastif
        } //filstof
      } // foreach

      return $insert_column;
  }

   /**
    * Salva no banco de dados os registros enviados pelo formulário
    * @param  array $insert_column - Colunas com os valores tratados
    * @return mixed bool para falso, e 1 para quantidoade inserida
    * @author André Luiz Pereira <andre@next4.com.br>
  */
  private function execBD($insert_column)
  {

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $column_key = array_keys($insert_column);
    $value_val = array_values($insert_column);

    $values_f  = array_map(function($n)use($db){
      return $db->quote($n);
    },$value_val);

    $columns = implode(',',$columns_f);
    $values = implode(',',$values_f);


     $return  = false;

        try{

          $query
          ->insert($db->quoteName('#__droide_forms_register'))
          ->columns($db->quoteName($column_key))
          ->values($values)
          ;

          $db->setQuery($query);

          $return = $db->execute();

        }catch (Exception $e) {
        
          JLog::addLogger(
            array(
                'text_file' => 'plugin_droidesavedb.php',
                'text_entry_format' => '{DATE} {TIME}  {MESSAGE}' 
            ),
            JLog::ERROR,
            'savedblog'
        );

        JLog::add($e, JLog::ERROR, 'savedblog');
        
    }

     return $return;

  }

  /**
    * Verifica se o campo é uma variavel caso contrario salva com texto estático
    * @param  string $text - texto contendo uma variavel [[minhaval]] ou texto estático
    * @param  array $returnTrigger - array de eventos disparados anteriores para consulta de variavel
    * @return string - retorna o texto que será salvo no banco
    * @author André Luiz Pereira <andre@next4.com.br>
  */
  private function checkVar($text, $returnTrigger)
  {

      preg_match("/\[\[([^<]+)\]\]+/", $text, $output_valor);

      $return = $text;

      if(isset($returnTrigger[$output_valor[1]]))
      {
        $return = $returnTrigger[$output_valor[1]];

      }

      return $return;
  }

  


}
