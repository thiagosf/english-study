# english-study

Read a url and show content, add able option to click in words to check in dictionay your definition and pronunciacion.

## Dev

Clone this repo and create `settings.php` file with same struture of `settings.default.php` and change with your settings.

```php
<?php

return [
  'OXFORD_APP_ID' => 'your_app_id',
  'OXFORD_APP_KEY' => 'your_app_key',
];

```

Init php server:

```bash
./dev
```

Access link [http://localhost:8080](http://localhost:8080)
