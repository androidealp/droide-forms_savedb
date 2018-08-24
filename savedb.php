<?php
/**
 * @package     mod_droideforms.Plugin
 * @subpackage  capcha
 *
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


  private function organizeArray($parans_bruto)
  {
    
    $total = count($parans_bruto['module_id']);

    $resultado = [];

    for ($i=0; $i <$total; $i++)
    { 
      $resultado[] = [
        'module_id'=>$parans_bruto['module_id'][$i],
        'custom_save'=>$parans_bruto['custom_save'][$i],
        'comando'=>$parans_bruto['custom_save'][$i],
      ];
    }

    return $tratado;
  }

  public function onDroideformsPosSend(&$module,  &$post, &$sucesso, &$log, &$returnTrigger)
  {

    $params_bruto = json_decode($this->params->get('addtable',0),true);

    $parans = [];

    if(in_array($module->id,$params_bruto['module_id']))
    {

        $parans = $this->organizeArray($params_bruto);

        print_r($parans);

        exit;

        // foreach ($arrayComandos as $id_form => $comand) {
        //   $execucao = $this->execBD($post,$module,$comand);
        // }

    }

  }


  public function onDroideformsPosSendError(&$module,  &$post, &$error, &$log, &$returnTrigger)
  {

      $params_bruto = json_decode($this->params->get('addtable',0),true);

      $parans = [];

      if(in_array($module->id,$params_bruto['module_id']))
      {
          $parans = $this->organizeArray($params_bruto);

          print_r($parans);

          exit;

          // foreach ($arrayComandos as $id_form => $comand) {
          //   $execucao = $this->execBD($post,$module,$comand);
          // }

      }



  }


  private function execBD($post, $module, $comand)
  {
    // insert into #__mensagemanonima (nome,email,telefone,assunto, mensagem) values(%s,%s,%s,%s,%s);
    // $db = JFactory::getDbo();
    // $nome = (isset($post['nome']) && $post['nome'] != '')?$post['nome']:'Anônimo';
    // $email = (isset($post['email']) && $post['email'] != '')?$post['nome']:'Anônimo';
    // $telefone = (isset($post['telefone']) && $post['telefone'] != '')?$post['telefone']:'Anônimo';
    // $assunto = $post['assunto'];
    // $id_cnb = $this->constructId($assunto);
    // $query = sprintf($comand,$db->quote($id_cnb), $db->quote($nome),$db->quote($email), $db->quote($telefone), $db->quote($assunto), $db->quote(nl2br(strip_tags($post['msn']))));

    // $db->setQuery($query);
    // return $db->execute();

  }


}
