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

### Building

1. Run python build script with filename

```
./build.py bot.zip
```

### 

3. Run the init script in your URL
```
https://my.telegram.callback/init
```

Congrats. Your telegram bot has been set up. Enjoy!
 
See https://github.com/teknologi-umum/telephant/bot-command-list.txt to tell `BotFather` in telegram this bot's commands.