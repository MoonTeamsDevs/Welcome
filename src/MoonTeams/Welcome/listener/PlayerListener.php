<?php

namespace MoonTeams\Welcome\listener;

use MoonTeams\Welcome\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class PlayerListener implements Listener {

    public static array $welcome = [];

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if (!$player->hasPlayedBefore()){
            self::$welcome[$player->getName()] = time() + 30;
            Server::getInstance()->broadcastMessage(str_replace(["{player}"], [$player->getName()], Main::getInstance()->getConfig()->get("welcome-message")));
        }
    }

}