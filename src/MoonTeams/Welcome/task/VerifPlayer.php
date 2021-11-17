<?php

namespace MoonTeams\Welcome\task;

use MoonTeams\Welcome\listener\PlayerListener;
use pocketmine\scheduler\Task;

class VerifPlayer extends Task {

    public function onRun(int $currentTick)
    {
        if (empty(PlayerListener::$welcome)) return;
        foreach (PlayerListener::$welcome as $player => $time) {
            if (time() >= $time){
                unset(PlayerListener::$welcome[$player]);
            }
        }
    }

}