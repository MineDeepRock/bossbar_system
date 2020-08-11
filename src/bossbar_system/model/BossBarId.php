<?php


namespace bossbar_system\model;



use pocketmine\entity\Entity;

class BossBarId
{
    private $value;

    public function __construct(int $value) {
        $this->value = $value;
    }

    static function asNew(): BossBarId {
        return new BossBarId(Entity::$entityCount++);
    }

    public function equals(?BossBarId $id): bool {
        if ($id === null)
            return false;

        return $this->value === $id->value;
    }

    /**
     * @return int
     */
    public function getValue(): int {
        return $this->value;
    }
}