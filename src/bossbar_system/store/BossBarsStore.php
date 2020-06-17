<?php

namespace bossbar_system\store;


use bossbar_system\models\BossBar;
use pocketmine\Player;

class BossBarsStore
{
    /**
     * @var BossBar[]
     * name => bossBar
     */
    static private $bossBars = [];

    static function getAll(): array {
        return self::$bossBars;
    }

    static function get(Player $player): ?BossBar {
        foreach (self::$bossBars as $name => $bossBar) {
            if ($name === $player->getName()) return $bossBar;
        }

        return null;
    }

    static function add(Player $player, BossBar $bossBar): void {
        self::$bossBars[$player->getName()] = $bossBar;
    }

    static function remove(Player $player): void {
        foreach (self::$bossBars as $name => $bossBar) {
            if ($name === $player->getName()) {
                unset(self::$bossBars[$name]);
            }
        }
    }

    static function update(Player $player, BossBar $bossBar): void {
        self::remove($player);
        self::add($player, $bossBar);
    }
}