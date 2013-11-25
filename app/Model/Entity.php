<?php
namespace Model;

/**
 * Description of Entity
 *
 * @author 
 */
class Entity {
    public function __get($property){
        if(method_exists($this, 'get' . ucfirst($property))){ 
            return call_user_func(array($this, 'get' . ucfirst($property))); 
        } 
        else{ 
            return $this->$property; 
        } 
    }
    
    public function __set($property, $value){
        if(method_exists($this, 'set' . ucfirst($property))){
            return call_user_func(array($this, 'set' . ucfirst($property)), $value); 
        } 
        else{
            $this->$property = $value; 
        } 
    } 
}
