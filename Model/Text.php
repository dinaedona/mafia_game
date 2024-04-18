<?php
class Text
{
    public const VALUES = [
        "I swear I'm not the mafia! I'm just a simple villager trying to survive.",
        "Guys, you've got it all wrong! I'm innocent, I promise.",
        "Why would I be the mafia? I've been helping the village from the start!",
        "I understand why you might suspect me, but I'm telling you, I'm not the mafia.",
        "Listen to me, if I was the mafia, why would I draw attention to myself like this?",
        "I'm just a regular villager like all of you. There's no reason for me to be the mafia.",
        "I've been trying to find the mafia just like everyone else. I'm not one of them!",
        "This is a misunderstanding! I've been working against the mafia this whole time.",
        "I know I seem suspicious, but that's just because I'm trying to figure out who the mafia is.",
        "Please, believe me! I would never betray the village like that.",
        "I've been with you all from the beginning. I wouldn't suddenly turn on you now.",
        "If you're going to accuse me, at least give me a chance to defend myself!",
        "I swear on my life, I am not the mafia. You have to trust me on this.",
        "I've been doing everything I can to help the village. Why would I do that if I was the mafia?",
        "I'm just a regular villager trying to survive in this chaos. I'm not the mafia, I swear.",
    ];


    public static function getRandomText(): string
    {
        return self::VALUES[array_rand(self::VALUES)];
    }
}