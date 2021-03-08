<?php


namespace bossbar_system\pmmp\service;


use bossbar_system\BossBar;
use LogicException;
use pocketmine\entity\Attribute;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\Player;

class UpdateBossBarPercentagePMMPService
{
    static function execute(Player $player, BossBar $bossBar, float $percentage): void {
        //$percentage = $percentage <= 0 ? 0.001 : $percentage;
        if ($percentage <= 0 || 1 < $percentage) {
            throw new LogicException("percentage can't be 0.0 and over 1.0\n if percentage is 0, the bossbar disappear.\n as to over 1.0, needless to say");
        }

        $attribute = Attribute::getAttribute(Attribute::HEALTH);
        $attribute->setMaxValue(1000);
        $attribute->setValue(1000 * $percentage);
        $upk = new UpdateAttributesPacket();
        $upk->entries = [$attribute];
        $upk->entityRuntimeId = $bossBar->getId()->getValue();
        $player->dataPacket($upk);

        $bossEventPacket = new BossEventPacket();
        $bossEventPacket->bossEid = $bossBar->getId()->getValue();
        $bossEventPacket->eventType = BossEventPacket::TYPE_HEALTH_PERCENT;
        $bossEventPacket->healthPercent = $percentage;
        $bossEventPacket->unknownShort = 0;
        $bossEventPacket->color = 0;
        $bossEventPacket->overlay = 0;
        $bossEventPacket->playerEid = 0;
        $player->dataPacket($bossEventPacket);
    }
}