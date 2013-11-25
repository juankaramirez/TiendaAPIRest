<?php
namespace Model;

/**
 * Description of producto
 *
 * @author 
 */

/** @Entity @Table(name="users") **/
class users extends Entity{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $username;
    /** @Column(type="string") **/
    protected $password;
    /** @Column(type="string") **/
    protected $roles;
    
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getRoles() { return $this->roles; }
    
}
