<?php

namespace bossbar_system\pmmp\service;

use bossbar_system\BossBar;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\Player;

class AddBossBarPMMPService
{
    static function execute(Player $player, BossBar $bossBar): void {

        $addActorPacket = new AddActorPacket();
        $addActorPacket->entityRuntimeId = $bossBar->getId()->getValue();
        $addActorPacket->type = "minecraft:slime";
        $addActorPacket->position = $player->getPosition();
        $addActorPacket->metadata = [
            Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, 0 ^ 1 << Entity::DATA_FLAG_SILENT ^ 1 << Entity::DATA_FLAG_INVISIBLE ^ 1 << Entity::DATA_FLAG_NO_AI],
            Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $bossBar->getTitle()],
            Entity::DATA_BOUNDING_BOX_WIDTH => [Entity::DATA_TYPE_FLOAT, 0],
            Entity::DATA_BOUNDING_BOX_HEIGHT => [Entity::DATA_TYPE_FLOAT, 0]
        ];
        $player->dataPacket($addActorPacket);

        $bossEventPacket = new BossEventPacket();
        $bossEventPacket->bossEid = $bossBar->getId()->getValue();
        $bossEventPacket->eventType = BossEventPacket::TYPE_SHOW;
        $bossEventPacket->title = $bossBar->getTitle();
        $bossEventPacket->healthPercent = $bossBar->getPercentage();
        $bossEventPacket->unknownShort = 0;
        $bossEventPacket->color = 0;
        $bossEventPacket->overlay = 0;
        $bossEventPacket->playerEid = 0;

        $player->dataPacket($bossEventPacket);
    }
}