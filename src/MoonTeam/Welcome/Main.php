<?php

namespace MoonTeam\Welcome;

use MoonTeam\Welcome\command\Welcome;
use MoonTeam\Welcome\listener\PlayerListener;
use MoonTeam\Welcome\task\VerifPlayer;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    public static self $instance;

    public static array $cooldown = [];

    public static function getInstance(): self{
        return self::$instance;
    }

    protected function onEnable(): void
    {
        self::$instance = $this;

        $this->getServer()->getCommandMap()->registerAll("Welcome", [
            new Welcome("welcome", "Welcome the new player.", "/welcome", [])
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);

        $this->getScheduler()->scheduleRepeatingTask(new VerifPlayer(), 20);
    }

}