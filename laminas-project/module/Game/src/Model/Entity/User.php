<?php
namespace Game\Model\Entity;

class User
{
    public $id_usuario;
    public $nombre;
    public $email;
    // Add other user properties as needed

    public function __construct(array $data = [])
    {
        $this->exchangeArray($data);
    }

    public function exchangeArray(array $data)
    {
        $this->id_usuario = $data['id_usuario'] ?? null;
        $this->nombre     = $data['nombre'] ?? null;
        $this->email      = $data['email'] ?? null;
    }
}
