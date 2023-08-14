<?php

namespace App\Class;

use Exception;

class User
{
    private $id;
    private $username;
    private $email;
    private $first_name;
    private $last_name;
    private $password;
    private $role;
    /**
     * Used to detect whether a user has been deleted by admin
     *
     * @var boolean default = false
     */
    private $deleted;
    private $picture;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        if (is_string($id) && (int)$id > 0) {
            $this->id = (int)$id;
        }
        if (is_int($id) && $id > 0) {
            $this->id = $id;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        return $this->picture = $picture;
    }

    private function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $methodName = 'set' . ucfirst($key);

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }
    }
    public function __toString()
    {
        return $this->username;
    }
}
