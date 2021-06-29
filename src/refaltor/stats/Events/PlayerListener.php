<?php

namespace refaltor\stats\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use refaltor\stats\Main;

class PlayerListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $plugin = Main::getInstance();
        $plugin->provider->registerUser($player, false);
    }
}