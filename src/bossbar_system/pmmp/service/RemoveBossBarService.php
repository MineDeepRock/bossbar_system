<?php


namespace bossbar_system\pmmp\service;


use bossbar_system\model\BossBarId;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\Player;

class RemoveBossBarService
{
    static function execute(Player $player, BossBarId $id): void {
        $rpk = new RemoveActorPacket();
        $rpk->entityUniqueId = $id->getValue();

        $player->dataPacket($rpk);
    }
}