<?php
namespace Model;

/**
 * Description of producto
 *
 * @author 
 */

/** @Entity @Table(name="producto") **/
class producto extends Entity{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;
    /** @Column(type="integer") **/
    public $catId;
    /** @Column(type="string") **/
    public $nombre;
    /** @Column(type="string") **/
    public $descr;
    /** @Column(type="integer") **/
    public $codigo;
    /** @Column(type="bigint") **/
    public $precio;
    /** @Column(type="integer") **/
    public $existencia;
    /** @Column(type="string") **/
    public $url;
    
    public function getId() { return $this->id; }
    public function getCatId() { return $this->catId; }
    public function getNombre() { return $this->nombre; }
    public function getDescr() { return $this->descr; }
    public function getCodigo() { return $this->codigo; }
    public function getPrecio() { return $this->precio; }
    public function getExistencia() { return $this->existencia; }
    public function getUrl() { return $this->url; }
    
}
