<?php

namespace MoonTeam\Welcome\task;

use MoonTeam\Welcome\listener\PlayerListener;
use pocketmine\scheduler\Task;

class VerifPlayer extends Task {

    public function onRun(): void
    {
        if (empty(PlayerListener::$welcome)) return;
        foreach (PlayerListener::$welcome as $player => $time) {
            if (time() >= $time){
                unset(PlayerListener::$welcome[$player]);
            }
        }
    }

}