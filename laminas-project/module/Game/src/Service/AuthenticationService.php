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

        if (!password_verify($this->password, $user->pass)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid credentials.']);
        }

        if ($user->baneado) {
            return new Result(Result::FAILURE, null, ['User is banned.']);
        }

        return new Result(Result::SUCCESS, $user->id_usuario, ['Authentication successful.']);
    }
}
