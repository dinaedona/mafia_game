<?php
session_start();
require_once 'AbstractController.php';
require_once '../Processor/GameProcessor.php';
require_once '../Processor/NightPhaseProcessor.php';
require_once '../Repository/GameRepository.php';
require_once '../Model/Response.php';
require_once '../Model/GameUserRole.php';

class GameController extends AbstractController
{
    private GameProcessor $gameProcessor;
    private NightPhaseProcessor $nightPhaseProcessor;
    private GameRepository $gameRepository;

    public function __construct()
    {
        parent::__construct();
        $this->gameProcessor = new GameProcessor();
        $this->nightPhaseProcessor = new NightPhaseProcessor();
        $this->gameRepository = new GameRepository();
    }

    public function start(): Response
    {
        $gameId = $this->gameProcessor->start();
        if ($gameId > 0) {
            return Response::create(1);
        }
        return Response::create(0, 'Something went wrong');
    }

    public function saveStatement(array $data): Response
    {
        $this->gameProcessor->saveStatements($_SESSION['game_id'], $_SESSION['user_id'], $data['text']);
        return Response::create(1);
    }

    public function eliminatePlayer(array $data): Response
    {
        return $this->gameProcessor->eliminatePlayer($_SESSION['game_id'], $_SESSION['user_id'], $data['user_id']);
    }

    public function detectiveEliminatePlayer(array $data): Response
    {
        return $this->gameProcessor->detectiveEliminatePlayer($_SESSION['game_id'], $_SESSION['user_id'], $data['user_id']);
    }

    public function playNightPhase(array $data): void
    {
        $userToEliminateOrProtect = $data['user_id'] ?: null;
        $this->nightPhaseProcessor->playNightPhase($_SESSION['game_id'], $_SESSION['user_id'], $userToEliminateOrProtect);
    }

    public function changeGameStatus(array $data): void
    {
        $this->gameRepository->updateStatus($_SESSION['game_id'], $data['status']);
    }
}

$game = new GameController();
echo $game->request();