# Mafia Game
## Description
Mafia Game is a web-based implementation of the classic party game Mafia. Players are assigned roles such as Mafia, Detective, Doctor, or Villager, and the game progresses through alternating night and day phases, where players must strategically eliminate suspected Mafia members or protect innocent villagers.

## Setup Process
To set up the Mafia Game project, follow these steps:

**Clone the Repository:** Clone the Mafia Game project repository from [GitHub](https://github.com/dinaedona/mafia_game)

**Web Server Configuration:** [Xampp](https://www.simplilearn.com/tutorials/php-tutorial/php-using-xampp)

**Database Configuration:** Update the DBConnection to match your database credentials.

## How to Play
To play the Mafia Game, follow these steps:

**Registration:** Sign up as a player by providing a username and password(Automatically you will be logged in.

**Login:** Log in with your credentials to access the game

**Start Page:** click the button to start game, system will assign a role, there is a Mafia, a Detective, a Doctor and 7 Villagers;

## Game Phases:

**Declaration Phase:** user writes why they should not eliminate him.

**Night Phase:**  Mafia secretly vote to eliminate a player and Doctor may choose to save a player from elimination.

**Day Phase:** all players vote to eliminate a suspected Mafia member. The player with the most votes is eliminated from the game if there are many users with same votes detective investigate and choose to user to eliminate.

**Game End:** The game continues with alternating night and day phases until one of the following conditions is met:

* All Mafia members are eliminated, and the villagers win.
* The Mafia outnumber the remaining players, and the Mafia wins.
