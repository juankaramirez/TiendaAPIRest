<?php
namespace Model;

/**
 * Description of categoria
 *
 * @author 
 */

/** @Entity @Table(name="categoria") **/
class categoria extends Entity{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;
    /** @Column(type="string") **/
    public $nombre;
    
    
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    
}
