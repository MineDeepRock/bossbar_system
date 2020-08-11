# BossBarSystem


### 生成
```php
use bossbar_system\BossBar;
use bossbar_system\model\BossBarType;
use pocketmine\Player;

/** @var Player $player */
$bossbar = new BossBar($player, new BossBarType("Lobby"), "Hello!", 1.0);
```

### 送り方
```php
use bossbar_system\BossBar;

/** @var BossBar $bossbar */
$bossbar->send();
```

### 取得
BossBarには `ID`と`TYPE` の２つがあります。
`ID`は一つ一つが固有なものです。ユーザーが指定することはありません。

`TYPE`は複数のボスバーが同じ値を持つことができますが、一つのプレイヤーが同じ`TYPE`のボスバーを持つことはできません。  
ユーザー自身が指定します。(BossBarTypesなどのクラスを作って管理するといいと思います)
```php
use bossbar_system\BossBar;
use bossbar_system\model\BossBarType;
use bossbar_system\model\BossBarId;
use pocketmine\Player;

/** @var BossBarId $bossbarId */
$bossbar = BossBar::findById($bossbarId);

/** @var Player $player */
/** @var BossBarType $bossbarType */
$bossbar = BossBar::findByType($player,$bossbarType);

$bossbar = BossBar::getBelongings($player);
```

### 削除
```php
use bossbar_system\BossBar;

/** @var BossBar $bossbar */
$bossbar->remove();
```

### 更新
```php
use bossbar_system\BossBar;

/** @var BossBar $bossbar */
$bossbar->updatePercentage(0.5);
$bossbar->updateTitle("50%");
```
