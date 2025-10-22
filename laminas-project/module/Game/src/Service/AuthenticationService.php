<?php
namespace Game\Service;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

use Laminas\Db\Adapter\AdapterInterface as DbAdapterInterface;

class AuthenticationService implements AdapterInterface
{
    private $username;
    private $password;
    private $dbAdapter;

    public function __construct(DbAdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function authenticate()
    {
        $table = new \Laminas\Db\TableGateway\TableGateway('mob_usuarios', $this->dbAdapter);
        $rowset = $table->select(['login' => $this->username]);
        $user = $rowset->current();

        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid credentials.']);
        }

        // NOTA: A verificação de senha em texto plano é insegura e temporária.
        // Será substituída por password_verify() após a migração das senhas.
        if ($this->password !== $user->pass) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid credentials.']);
        }

        // A lógica para verificar o banimento será adicionada aqui.

        return new Result(Result::SUCCESS, $user, ['Authentication successful.']);
    }
}
