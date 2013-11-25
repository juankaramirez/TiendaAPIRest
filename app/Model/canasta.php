<?php
namespace Model;

/**
 * Description of producto
 *
 * @author 
 */

/** @Entity @Table(name="canasta") **/
class canasta extends Entity{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $idCanasta;
    /**
   
     * @Column(type="integer") 
     * **/
    protected $idProducto;
    
    /**

     * @Column(type="string") 
     * **/
    protected $username;
    /** @Column(type="string") **/
    protected $fecha;
    
    
    public function getId() { return $this->id; }
    public function getIdProducto() { return $this->idProducto; }
    public function getUsername() { return $this->username; }
    public function getFecha() { return $this->fecha; }
    
}
