# telephant

Telegram bot in php

### Setup

1. Installation

```
git clone https://github.com/teknologi-umum/telephant
```

2. Create telegram bot and onfig `.env`. Take reference from `.env.example`

```
DB_HOST=127.0.0.1
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password

TELEGRAM_BOT_API_KEY=my_telegram:api_key
TELEGRAM_BOT_USERNAME=my_telegram_username_bot
TELEGRAM_BOT_WEBHOOK_URL=https://my.telegram.callback/hook
```

Make sure the root of the `telephant` app is located in `https://my.telegram.callback`

3. Install `composer` dependencies in project root directory
```
composer install
```

### Building

1. Run python build script with filename

```
./build.py bot.zip
```

### Setting webhook URL

3. Call the init http endpoint in your URL
```
https://my.telegram.callback/init
```
 
Congrats. Your telegram bot has been set up. Enjoy!
 
See https://github.com/teknologi-umum/telephant/blob/main/bot-command-list.txt to tell `BotFather` in telegram this bot's commands.