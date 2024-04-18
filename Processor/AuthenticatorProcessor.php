<?php
require_once '../Repository/UserRepository.php';
require_once '../Validator/PasswordValidator.php';
require_once '../Model/Response.php';
require_once '../Model/User.php';

class AuthenticatorProcessor
{
    private UserRepository $userRepository;
    private PasswordValidator $passwordValidator;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->passwordValidator = new PasswordValidator();
    }

    public function login(User $user): Response
    {
        $userEntry = $this->userRepository->findOneByUsernameAndPassword($user->getUsername());
        if (!$userEntry || !password_verify($user->getPassword(), $userEntry->getPassword())) {
            return Response::create(0, 'Invalid email or password');
        }
        session_start(); // Start the session
        $_SESSION["user_id"] = $userEntry->getId();
        $_SESSION["username"] = $user->getUsername();
        return Response::create(1);
    }

    public function register(User $user): Response
    {
        if ($this->userRepository->usernameExists($user->getUsername())) {
            return Response::create(0, 'This username is already in use. Please choose another one.');
        }
        $passwordValidationResponse = $this->passwordValidator->validate($user->getPassword());
        if (!$passwordValidationResponse->isSuccess()) {
            return $passwordValidationResponse;
        }
        $hashed_password = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $userId = $this->userRepository->insert($user->getUsername(), $hashed_password);
        session_start();
        $_SESSION["user_id"] = $userId;
        $_SESSION["username"] = $user->getUsername();
        return Response::create(1);
    }
}