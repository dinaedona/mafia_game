<?php
require_once '../Database/DBConnection.php';
require_once 'UserRoleProcessor.php';
require_once '../Repository/GameRepository.php';
require_once '../Repository/UserRepository.php';
require_once '../Repository/UserRoleRepository.php';
require_once '../Repository/GameUserRepository.php';
require_once '../Repository/GameUserHistoryRepository.php';
require_once '../Model/Text.php';

class GameProcessor
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


    public function start(): int
    {
        return $this->connection->runInTransaction(function () {
            // assign roles to users;
            $userRoles = $this->userRoleProcessor->assignRoles();
            $userToEliminate = $this->userRoleProcessor->getVillagerToEliminate($userRoles);
            $userToProtect = $this->userRoleProcessor->getVillagerToEliminate($userRoles);
            $gameId = $this->gameRepository->insert(Game::fromValues(null, 'Start', 1, $userToEliminate->getId(), $userToProtect->getId()));
            $this->gameUserRepository->insert($gameId, $userRoles);
            $_SESSION['game_id'] = $gameId;
            return $gameId;
        });
    }

    public function saveStatements(int $gameId, int $loggedInUserId, $text): void
    {
        $userTexts[(string)$loggedInUserId] = $text;
        $users = $this->userRepository->findPcPlayers();
        foreach ($users as $user) {
            $userTexts[(string)$user->getId()] = Text::getRandomText();
        }
        $this->gameUserRepository->updateText($gameId, $userTexts);
        $this->gameRepository->updateStatus($gameId, 'Day');
    }

    public function endGame(int $gameId)
    {
        $this->gameRepository->updateStatus($gameId, 'End');
        unset($_SESSION['game_id']);
    }

    public function eliminatePlayer(int $gameId, int $loggedInUserId, int $userToEliminate): Response
    {
        // list of eliminations for all users
        $usersToEliminate = $this->trackHistoryOfElimination($gameId, $loggedInUserId, $userToEliminate);
        //most voted users to be eliminated
        $eliminatedUserIds = $this->getEliminatedUserByVotes($gameId, $loggedInUserId, $usersToEliminate);
        // return list of players to investigate detective for them
        if (count($eliminatedUserIds) > 1) {
            return Response::create(-1, 'Detective should investigate players', $eliminatedUserIds);
        }
        $eliminatedUserId = $eliminatedUserIds[0];
        return $this->checkForGameRules($gameId, $eliminatedUserId);
    }

    public function detectiveEliminatePlayer(int $gameId, int $loggedInUserId, int $userToEliminate): Response
    {
        // track history for eliminations
        $this->trackHistoryOfDetectiveElimination($gameId, $loggedInUserId, $userToEliminate);
        return $this->checkForGameRules($gameId, $userToEliminate);
    }

    private function checkForGameRules(int $gameId, int $eliminatedUserId): Response
    {
        $game = $this->gameRepository->findOneById($gameId);
        if ($game->getProtectUserId() === $eliminatedUserId) { // check if this user was protected by doctor
            return Response::create(0, 'User was protected by Doctor');
        }
        $this->gameUserRepository->updateUserStatus($gameId, $eliminatedUserId); // change status of player, from alive to eliminated

        $eliminatedUserRole = $this->userRoleRepository->findOneByGameIdAndUserId($gameId, $eliminatedUserId);
        if ($eliminatedUserRole->getRole()->isMafia()) { // check if eliminated user was mafia to end game
            return Response::create(1, 'Mafia is eliminated!');
        }
        // check if there is any villager alive to continue game
        $aliveVillagers = $this->gameUserRepository->findAliveVillagersByGameId($gameId);
        if (count($aliveVillagers) < 1) {
            return Response::create(-2, 'Game over! Villagers are eliminated');
        }
        $this->gameRepository->updateStatus($gameId, 'Night');
        return Response::create(0, 'Game continues...');
    }

    /**
     * @param int $gameId
     * @param int $loggedInUserId
     * @param $userToEliminate
     * @return mixed[]
     */
    private function trackHistoryOfElimination(int $gameId, int $loggedInUserId, int $userToEliminate): array
    {
        $usersToEliminate[(string)$loggedInUserId] = $userToEliminate;
        $users = $this->gameUserRepository->findActiveUsersByGameId($gameId);
        $otherPlayers = $this->getUserIds($users, $loggedInUserId);
        //create a random list who eliminates who
        foreach ($otherPlayers as $player) {
            $possibleUsersToEliminate = $this->getUserIds($users, $player);
            $usersToEliminate[(string)$player] = $possibleUsersToEliminate[array_rand($possibleUsersToEliminate)];
        }
        // save it in db
        $this->gameUserHistoryRepository->insert($gameId, $usersToEliminate);
        return $usersToEliminate;
    }

    /**
     * @param int $gameId
     * @param int $loggedInUserId
     * @param $userToEliminate
     * @return mixed[]
     */
    private function trackHistoryOfDetectiveElimination(int $gameId, int $loggedInUserId, int $userToEliminate): array
    {
        $usersToEliminate[(string)$loggedInUserId] = $userToEliminate;
        // save it in db
        $this->gameUserHistoryRepository->insert($gameId, $usersToEliminate);
        return $usersToEliminate;
    }

    /*if the list of most voted users has more than one user and active user role is detective,
    then detective needs to investigate those users again, if not detective we get randomly one user from that list */
    private function getEliminatedUserByVotes(int $gameId, int $loggedInUserId, array $usersToEliminate): array
    {
        $mostVotedUsers = $this->findMostVotedUser($usersToEliminate);
        $eliminatedUserId = $mostVotedUsers[0];
        if (count($mostVotedUsers) > 1) {
            $userRole = $this->userRoleRepository->findOneByGameIdAndUserId($gameId, $loggedInUserId);
            if ($userRole->getRole()->isDetective()) {
                return $mostVotedUsers;
            }
            $eliminatedUserId = $mostVotedUsers[array_rand($mostVotedUsers)];
        }
        return [$eliminatedUserId];
    }

    /**
     * @param User[] $users
     */
    private function getUserIds(array $users, int $excludeId)
    {
        $ids = [];
        foreach ($users as $user) {
            if ($user->getId() === $excludeId) {
                continue;
            }
            $ids[] = $user->getId();
        }
        return $ids;
    }

    // find which users where most voted to eliminate
    function findMostVotedUser(array $users): array
    {
        $counts = array_count_values($users);
        $maxCount = max($counts);
        $mostVotedUsers = array_keys($counts, $maxCount);
        return $mostVotedUsers;
    }
}