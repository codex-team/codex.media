#!/bin/bash

# Create dump page
echo "<meta http-equiv="refresh" content="3"> Project is being updated. Wait a little bit..." > install.php

# Pull last updates from remote repository
git pull

# Update composer packages
composer install

# Install Node.js packages
npm i

# Build js and css
npm run build

# Remove dump page
rm install.php

# Find domain value in .env file
DOMAIN=`sed -n 's/^ *DOMAIN *= *//p' .env`

# Prepare message
MESSAGE="CodeX Media has been deployed to the production environment on $DOMAIN"

# Send notification
curl -X POST https://notify.bot.ifmo.su/u/G96UXE0H -d "message=$MESSAGE"