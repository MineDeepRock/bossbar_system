<?php

namespace bossbar_system;

use bossbar_system\pmmp\service\AddBossBarPMMPService;
use bossbar_system\pmmp\service\RemoveBossBarService;
use bossbar_system\pmmp\service\UpdateBossBarLocationPMMPService;
use bossbar_system\pmmp\service\UpdateBossBarPercentagePMMPService;
use bossbar_system\pmmp\service\UpdateBossBarTitlePMMPService;
use bossbar_system\store\BossBarsStore;
use pocketmine\entity\Entity;
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

        //PMMP上での追加
        AddBossBarPMMPService::execute($player, $this);
    }

    public function remove(Player $player) {
        BossBarsStore::remove($player);

        //PMMP上での削除
        RemoveBossBarService::execute($player, $this->id);
    }

    public function updatePercentage(Player $player, float $percentage) {
        $this->percentage = $percentage;
        BossBarsStore::update($player, $this);

        //PMMP上での更新
        UpdateBossBarPercentagePMMPService::execute($player, $this, $percentage);
    }

    public function updateTitle(Player $player, string $title) {
        $this->title = $title;
        BossBarsStore::update($player, $this);

        //PMMP上での更新
        UpdateBossBarTitlePMMPService::execute($player, $this, $title);
    }

    public function updateLocationInformation(Player $player) {
        UpdateBossBarLocationPMMPService::execute($player, $this->id);
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

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return float
     */
    public function getPercentage(): float {
        return $this->percentage;
    }
}
