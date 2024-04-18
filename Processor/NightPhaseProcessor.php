<?php

require_once '../Database/DBConnection.php';
require_once 'UserRoleProcessor.php';
require_once '../Repository/GameRepository.php';
require_once '../Repository/UserRepository.php';
require_once '../Repository/UserRoleRepository.php';
require_once '../Repository/GameUserRepository.php';

class NightPhaseProcessor
{

    private UserRoleProcessor $userRoleProcessor;
    private GameRepository $gameRepository;
    private GameUserRepository $gameUserRepository;
    private DBConnection $connection;
    private UserRepository $userRepository;
    private UserRoleRepository $userRoleRepository;
    private GameUserHistoryRepository $gameUserHistoryRepository;

    public function __construct()
    {
        $this->userRoleProcessor = new UserRoleProcessor();
        $this->gameRepository = new GameRepository();
        $this->gameUserRepository = new GameUserRepository();
        $this->userRepository = new UserRepository();
        $this->userRoleRepository = new UserRoleRepository();
        $this->gameUserHistoryRepository = new GameUserHistoryRepository();
        $this->connection = DBConnection::connect();
    }

    public function playNightPhase(int $gameId, int $loggedInUserId, ?int $userEliminateOrProtect): void
    {
        $this->connection->runInTransaction(function () use ($gameId, $loggedInUserId, $userEliminateOrProtect) {
            $userRole = $this->userRoleRepository->findOneByGameIdAndUserId($gameId, $loggedInUserId);
            $game = $this->gameRepository->findOneById($gameId);

            $aliveUsers = $this->gameUserRepository->findAliveByGameId($gameId);
            $eliminateUserId = $this->getUserToEliminate($userRole, $userEliminateOrProtect, $aliveUsers);
            $protectUserId = $this->getUserToProtect($gameId, $userRole, $userEliminateOrProtect, $aliveUsers);
            $newGame = Game::fromValues($game->getId(), $game->getStatus(), ($game->getDay() + 1), $eliminateUserId, $protectUserId);
            $this->gameRepository->update($newGame);
        });
    }

    /**
     * @param UserRole $userRole
     * @param int|null $userEliminateOrProtect
     * @param GameUserRole[] $gameUserRoles
     * @return int|null
     */

    private function getUserToEliminate(UserRole $userRole, ?int $userEliminateOrProtect, array $gameUserRoles): ?int
    {
        if ($userRole->getRole()->isMafia()) {
            return $userEliminateOrProtect;
        }
        return $this->getRandomNonMafiaUser($gameUserRoles)->getId();
    }

    /**
     * @param int $gameId
     * @param UserRole $userRole
     * @param int|null $userEliminateOrProtect
     * @param GameUserRole[] $gameUserRoles
     * @return int|null
     */
    private function getUserToProtect(int $gameId, UserRole $userRole, ?int $userEliminateOrProtect, array $gameUserRoles): ?int
    {
        $doctor = $this->gameUserRepository->findDoctorByGameId($gameId);
        if (!$doctor->isAlive()) {
            return null;
        }
        if ($userRole->getRole()->isDoctor()) {
            return $userEliminateOrProtect;
        }
        return $this->getRandomUser($gameUserRoles)->getId();
    }

    /**
     * @param GameUserRole[] $gameUserRoles
     * @return User
     */
    private function getRandomNonMafiaUser(array $gameUserRoles): User
    {
        $users = [];
        foreach ($gameUserRoles as $gameUserRole) {
            if (!$gameUserRole->getUserRole()->getRole()->isMafia()) {
                $users[] = $gameUserRole->getUserRole()->getUser();
            }
        }

        $userIndex = array_rand($users);
        return $users[$userIndex];
    }

    /**
     * @param GameUserRole[] $gameUserRoles
     * @return User
     */
    private function getRandomUser(array $gameUserRoles): User
    {
        $users = [];
        foreach ($gameUserRoles as $gameUserRole) {
            $users[] = $gameUserRole->getUserRole()->getUser();
        }

        $userIndex = array_rand($users);
        return $users[$userIndex];
    }
}