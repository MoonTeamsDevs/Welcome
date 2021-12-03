<?php

namespace MoonTeam\Welcome\command;

use MoonTeam\Welcome\listener\PlayerListener;
use MoonTeam\Welcome\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\Server;

class Welcome extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("welcome")) {
                if (!isset(Main::$cooldown[$sender->getName()])) {
                    if (empty(PlayerListener::$welcome)) {
                        $sender->sendMessage(Main::getInstance()->getConfig()->get("prefix") . Main::getInstance()->getConfig()->get("no-new-players"));
                        return;
                    } else {
                        $res = [];
                        foreach (PlayerListener::$welcome as $player => $time) {
                            $res[] = $player;
                        }
                        $players = implode(", ", $res);
                        Server::getInstance()->broadcastMessage(str_replace(["{player}", "{players}"], [$sender->getName(), $players], Main::getInstance()->getConfig()->get("welcome-player-message")));
                        Main::$cooldown[$sender->getName()] = time() + Main::getInstance()->getConfig()->get("cooldown");
                        if (Main::getInstance()->getConfig()->get("enable-command")) {
                            if (!empty(Main::getInstance()->getConfig()->get("commands"))) {
                                foreach (Main::getInstance()->getConfig()->get("commands") as $command) {
                                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace(["{player}"], [$sender->getName()], $command));
                                }
                            }
                        }
                    }
                } else {
                    if (time() >= Main::$cooldown[$sender->getName()]) {
                        if (empty(PlayerListener::$welcome)) {
                            $sender->sendMessage(Main::getInstance()->getConfig()->get("prefix") . Main::getInstance()->getConfig()->get("no-new-players"));
                            return;
                        } else {
                            $res = [];
                            foreach (PlayerListener::$welcome as $player => $time) {
                                $res[] = $player;
                            }
                            $players = implode(", ", $res);
                            Server::getInstance()->broadcastMessage(str_replace(["{player}", "{players}"], [$sender->getName(), $players], Main::getInstance()->getConfig()->get("welcome-player-message")));
                            Main::$cooldown[$sender->getName()] = time() + Main::getInstance()->getConfig()->get("cooldown");
                            if (Main::getInstance()->getConfig()->get("enable-command")) {
                                if (!empty(Main::getInstance()->getConfig()->get("commands"))) {
                                    foreach (Main::getInstance()->getConfig()->get("commands") as $command) {
                                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace(["{player}"], [$sender->getName()], $command));
                                    }
                                }
                            }
                        }
                    } else {
                        $sender->sendMessage(Main::getInstance()->getConfig()->get("prefix") . str_replace(["{time}"], [Main::$cooldown[$sender->getName()] - time()], Main::getInstance()->getConfig()->get("cooldown-message")));
                        return;
                    }
                }
            }
        }
    }

}