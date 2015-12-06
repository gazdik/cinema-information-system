<?php

namespace AppBundle\Entity\Form;

class UserForm
{
    protected $email;
    protected $name;
    protected $newPass = '';
    protected $newPassRetyped = '';
    protected $oldPass;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getNewPass() {
        return $this->newPass;
    }

    public function setNewPass($newPass) {
        $this->newPass = $newPass;
    }

    public function getNewPassRetyped() {
        return $this->newPassRetyped;
    }

    public function setNewPassRetyped($newPassRetyped) {
        $this->newPassRetyped = $newPassRetyped;
    }

    public function getOldPass() {
        return $this->oldPass;
    }

    public function setOldPass($oldPass) {
        $this->oldPass = $oldPass;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

}
