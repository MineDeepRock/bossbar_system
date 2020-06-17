<?php

namespace bossbar_system;

use bossbar_system\models\BossBar;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $bossBar = BossBar::get($player);
        if ($bossBar !== null) $bossBar->remove($player);
    }

    public function onTeleport(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $bossBar = BossBar::get($player);
            if ($bossBar !== null) $bossBar->updateLocationInformation($player);
        }
    }
}