<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: win
 * Date: 2018/10/17
 * Time: 11:05
 */
namespace App\Traits;


trait MailTrait {
   public function checkEmail(){
       $to = $this->to;
       if(empty($to)){
          return ;
       }
       $address = $to[0]['address'];
       $to[0]['address'] = getTestEmailList($address);
       $this->to = $to;
       return $this;
   }
}