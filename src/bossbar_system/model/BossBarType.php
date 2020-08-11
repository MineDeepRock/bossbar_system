<?php


namespace bossbar_system\model;


class BossBarType
{
    private $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public function equals(?BossBarType $id): bool {
        if ($id === null)
            return false;

        return $this->value === $id->value;
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return $this->value;
    }
}