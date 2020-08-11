<?php

namespace bossbar_system;

use bossbar_system\model\BossBarId;
use bossbar_system\model\BossBarType;
use bossbar_system\pmmp\service\AddBossBarPMMPService;
use bossbar_system\pmmp\service\RemoveBossBarService;
use bossbar_system\pmmp\service\UpdateBossBarLocationPMMPService;
use bossbar_system\pmmp\service\UpdateBossBarPercentagePMMPService;
use bossbar_system\pmmp\service\UpdateBossBarTitlePMMPService;
use bossbar_system\store\BossBarsStore;
use pocketmine\Player;

class BossBar
{
    /**
     * @var Player
     */
    private $owner;
    /**
     * @var BossBarId
     */
    private $id;
    /**
     * @var BossBarType
     */
    private $type;
    /**
     * @var string
     */
    private $title;
    /**
     * @var float
     */
    private $percentage;

    public function __construct(Player $player, BossBarType $type, string $title, float $percentage) {
        $this->owner = $player;
        $this->type = $type;
        $this->id = BossBarId::asNew();

        $this->title = $title;
        $this->percentage = $percentage;
    }

    public function send(): void {
        BossBarsStore::add($this);

        //PMMP上での追加
        AddBossBarPMMPService::execute($this->owner, $this);
    }

    public function remove() {
        BossBarsStore::remove($this->getId());

        //PMMP上での削除
        RemoveBossBarService::execute($this->owner, $this->id);
    }

    public function updatePercentage(float $percentage) {
        $this->percentage = $percentage;
        BossBarsStore::update($this);

        //PMMP上での更新
        UpdateBossBarPercentagePMMPService::execute($this->owner, $this, $percentage);
    }

    public function updateTitle(string $title) {
        $this->title = $title;
        BossBarsStore::update($this);

        //PMMP上での更新
        UpdateBossBarTitlePMMPService::execute($this->owner, $this, $title);
    }

    public function updateLocationInformation(Player $player) {
        UpdateBossBarLocationPMMPService::execute($player, $this->id);
    }

    static function findById(BossBarId $bossBarId): ?BossBar {
        return BossBarsStore::findById($bossBarId);
    }

    static function getBelongings(Player $player): array {
        return BossBarsStore::getBelongings($player);
    }

    static function findByType(Player $player, BossBarType $type): ?BossBar {
        return BossBarsStore::findByType($player, $type);
    }

    /**
     * @return BossBarId
     */
    public function getId(): BossBarId {
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

    /**
     * @return Player
     */
    public function getOwner(): Player {
        return $this->owner;
    }

    /**
     * @return BossBarType
     */
    public function getType(): BossBarType {
        return $this->type;
    }
}
