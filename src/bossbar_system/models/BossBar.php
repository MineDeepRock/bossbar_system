<?php

namespace bossbar_system\models;

use bossbar_system\store\BossBarsStore;
use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\Player;

class BossBar
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var float
     */
    private $percentage;

    public function __construct(string $title, float $percentage) {
        $this->id = Entity::$entityCount++;
        $this->title = $title;
        $this->percentage = $percentage;
    }

    public function send(Player $player): void {
        BossBarsStore::add($player, $this);

        $addActorPacket = new AddActorPacket();
        $addActorPacket->entityRuntimeId = $this->id;
        $addActorPacket->type = "minecraft:slime";
        $addActorPacket->position = $player->getPosition();
        $addActorPacket->metadata = [
            Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, 0 ^ 1 << Entity::DATA_FLAG_SILENT ^ 1 << Entity::DATA_FLAG_INVISIBLE ^ 1 << Entity::DATA_FLAG_NO_AI],
            Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title],
            Entity::DATA_BOUNDING_BOX_WIDTH => [Entity::DATA_TYPE_FLOAT, 0],
            Entity::DATA_BOUNDING_BOX_HEIGHT => [Entity::DATA_TYPE_FLOAT, 0]
        ];
        $player->dataPacket($addActorPacket);

        $bossEventPacket = new BossEventPacket();
        $bossEventPacket->bossEid = $this->id;
        $bossEventPacket->eventType = BossEventPacket::TYPE_SHOW;
        $bossEventPacket->title = $this->title;
        $bossEventPacket->healthPercent = $this->percentage;
        $bossEventPacket->unknownShort = 0;
        $bossEventPacket->color = 0;
        $bossEventPacket->overlay = 0;
        $bossEventPacket->playerEid = 0;

        $player->dataPacket($bossEventPacket);
    }

    public function remove(Player $player) {
        BossBarsStore::remove($player);
        $rpk = new RemoveActorPacket();
        $rpk->entityUniqueId = $this->id;

        $player->dataPacket($rpk);
    }

    public function updatePercentage(Player $player, float $percentage) {
        //$percentage = $percentage <= 0 ? 0.001 : $percentage;
        $this->percentage = $percentage;
        BossBarsStore::update($player, $this);

        $attribute = Attribute::getAttribute(Attribute::HEALTH);
        $attribute->setMaxValue(1000);
        $attribute->setValue(1000 * $this->percentage);
        $upk = new UpdateAttributesPacket();
        $upk->entries = [$attribute];
        $upk->entityRuntimeId = $this->id;
        $player->dataPacket($upk);

        $bossEventPacket = new BossEventPacket();
        $bossEventPacket->bossEid = $this->id;
        $bossEventPacket->eventType = BossEventPacket::TYPE_HEALTH_PERCENT;
        $bossEventPacket->healthPercent = $this->percentage;
        $bossEventPacket->unknownShort = 0;
        $bossEventPacket->color = 0;
        $bossEventPacket->overlay = 0;
        $bossEventPacket->playerEid = 0;
        $player->dataPacket($bossEventPacket);

        $player->dataPacket($bossEventPacket);
    }

    public function updateTitle(Player $player, string $title) {
        $this->title = $title;
        BossBarsStore::update($player, $this);

        $setActorPacket = new SetActorDataPacket();
        $setActorPacket->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $title]];
        $setActorPacket->entityRuntimeId = $this->id;

        $player->dataPacket($setActorPacket);

        $bossEventPacket = new BossEventPacket();
        $bossEventPacket->bossEid = $this->id;
        $bossEventPacket->eventType = BossEventPacket::TYPE_TITLE;
        $bossEventPacket->title = $this->title;
        //$bossEventPacket->healthPercent = self::$percentage;
        $bossEventPacket->unknownShort = 0;
        $bossEventPacket->color = 0;
        $bossEventPacket->overlay = 0;
        $bossEventPacket->playerEid = 0;

        $player->dataPacket($bossEventPacket);
    }

    public function updateLocationInformation(Player $player) {
        $moveActorPacket = new MoveActorAbsolutePacket();
        $moveActorPacket->entityRuntimeId = $this->id;
        $moveActorPacket->flags |= MoveActorAbsolutePacket::FLAG_TELEPORT;
        $moveActorPacket->position = $player->getPosition();
        $moveActorPacket->xRot = 0;
        $moveActorPacket->yRot = 0;
        $moveActorPacket->zRot = 0;

        $player->dataPacket($moveActorPacket);
    }

    static function get(Player $player): ?BossBar {
        return BossBarsStore::get($player);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
}
