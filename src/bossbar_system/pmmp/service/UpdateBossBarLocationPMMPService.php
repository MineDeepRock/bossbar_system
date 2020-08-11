<?php


namespace bossbar_system\pmmp\service;


use bossbar_system\model\BossBarId;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\Player;

class UpdateBossBarLocationPMMPService
{
    static function execute(Player $player, BossBarId $id): void {
        $moveActorPacket = new MoveActorAbsolutePacket();
        $moveActorPacket->entityRuntimeId = $id->getValue();
        $moveActorPacket->flags |= MoveActorAbsolutePacket::FLAG_TELEPORT;
        $moveActorPacket->position = $player->getPosition();
        $moveActorPacket->xRot = 0;
        $moveActorPacket->yRot = 0;
        $moveActorPacket->zRot = 0;

        $player->dataPacket($moveActorPacket);
    }
}