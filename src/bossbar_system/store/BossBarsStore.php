<?php

namespace bossbar_system\store;


use bossbar_system\BossBar;
use bossbar_system\model\BossBarId;
use bossbar_system\model\BossBarType;
use pocketmine\Player;

class BossBarsStore
{
    /**
     * @var BossBar[]
     */
    static private $bossBars = [];

    static function getAll(): array {
        return self::$bossBars;
    }

    /**
     * @param Player $player
     * @return BossBar[]
     */
    static function getBelongings(Player $player): array {
        $result = [];
        foreach (self::$bossBars as $bossBar) {
            if ($bossBar->getOwner()->getName() === $player->getName()) {
                $result[] = $bossBar;
            }
        }

        return $result;
    }

    static function findById(BossBarId $id): ?BossBar {
        foreach (self::$bossBars as $bossBar) {
            if ($bossBar->getId()->equals($id)) return $bossBar;
        }

        return null;
    }

    static function findByType(Player $player, BossBarType $type): ?BossBar {

        foreach (self::$bossBars as $bossBar) {
            if ($bossBar->getType()->equals($type) && $bossBar->getOwner()->getName() === $player->getName()) {
                return $bossBar;
            }
        }

        return null;
    }

    static function add(BossBar $bossBar): void {
        if (self::findById($bossBar->getId()) !== null) {
            throw new \LogicException("{$bossBar->getId()->getValue()}はすでに存在します");
        }

        if (self::findByType($bossBar->getOwner(), $bossBar->getType()) !== null) {
            throw new \LogicException("ownerおよびtypeが等しいボスバーがすでに存在します");
        }

        self::$bossBars[] = $bossBar;
    }

    static function remove(BossBarId $id): void {
        foreach (self::$bossBars as $index => $bossBar) {
            if ($bossBar->getId()->equals($id)) unset(self::$bossBars[$index]);
        }

        self::$bossBars = array_values(self::$bossBars);
    }

    static function update(BossBar $bossBar): void {
        self::remove($bossBar->getId());
        self::add($bossBar);
    }
}