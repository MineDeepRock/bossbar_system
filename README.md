# BossBarSystem

use文
```php
use bossbar_system\models\BossBar;
```

init
```php
$bossbar = new BossBar("Hello!", 50);
```

get
```php
$bossbar = BossBar::get($player);
```

send
```php
$bossbar->send($player);
```

remove
```php
$bossbar->remove($player);
```

update
```php
$bossbar->updatePercentage($player,0.5);
$bossbar->updateTitle($player,"50%");
```