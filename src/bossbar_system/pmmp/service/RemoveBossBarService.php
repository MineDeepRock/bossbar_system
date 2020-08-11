<?php


namespace bossbar_system\pmmp\service;


use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\Player;

class RemoveBossBarService
{
    static function execute(Player $player, int $id): void {
        $rpk = new RemoveActorPacket();
        $rpk->entityUniqueId = $id;

        $player->dataPacket($rpk);
    }
}