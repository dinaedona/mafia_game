<?php
require_once 'AbstractController.php';
require_once '../Repository/UserRepository.php';
require_once '../Processor/AuthenticatorProcessor.php';
require_once '../Validator/PasswordValidator.php';
require_once '../Model/Response.php';

class AuthenticatorController extends AbstractController
{
    private AuthenticatorProcessor $processor;

    public function __construct()
    {
        parent::__construct();
        $this->processor = new AuthenticatorProcessor();
    }

    public function login(array $data): Response
    {
        $user = User::fromArray($data);
        return $this->processor->login($user);
    }

    public function register(array $data): Response
    {
        $user = User::fromArray($data);
        return $this->processor->register($user);
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
    }
}

$authenticator = new AuthenticatorController();
echo $authenticator->request();
