<?php


namespace bossbar_system\pmmp\service;


use bossbar_system\BossBar;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\Player;

class UpdateBossBarTitlePMMPService
{
    static function execute(Player $player, BossBar $bossBar, string $title): void {

        $setActorPacket = new SetActorDataPacket();
        $setActorPacket->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $title]];
        $setActorPacket->entityRuntimeId = $bossBar->getId();

        $player->dataPacket($setActorPacket);

        $bossEventPacket = new BossEventPacket();
        $bossEventPacket->bossEid = $bossBar->getId();
        $bossEventPacket->eventType = BossEventPacket::TYPE_TITLE;
        $bossEventPacket->title = $title;
        $bossEventPacket->unknownShort = 0;
        $bossEventPacket->color = 0;
        $bossEventPacket->overlay = 0;
        $bossEventPacket->playerEid = 0;

        $player->dataPacket($bossEventPacket);
    }
}