# ! /bin/sh
# deploy.sh
#
# = = = = = = = = = = = = = = = = = = = = = = = = = = = =
# Deploy / Update / Init Script
# = = = = = = = = = = = = = = = = = = = = = = = = = = = =
# Possible commands are:
# 1. init - This will initialize the system from scratch
# 2. update - Only use this to update production environment
# = = = = = = = = = = = = = = = = = = = = = = = = = = = =
# Both commands support the parameter --dev
# That will ensure the assets will be compiled as development versions
# and also include development dependencies

COMMAND=$1
PARAMETER=$2

echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
echo "# Deploy Application Script"
echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
if [ "$COMMAND" != "init" ] && [ "$COMMAND" != "update" ]; then
    echo "Usage:"
    echo "./deploy.sh [init, update] (--dev)"
    exit
fi

# Do some prep work first

# Set the application to maintanance
# and update to the latest git version
php artisan down
git pull --recurse-submodules

# = = = = = = = = = = = = = = = = = = = = = = = = = = = =
# Test for the .env file
# = = = = = = = = = = = = = = = = = = = = = = = = = = = =
# If it does exist we can go ahead
# Else we copy it from our tempalte and end with a message to the user to adapt the file to his/her needs
# = = = = = = = = = = = = = = = = = = = = = = = = = = = =
if [ -f ".env" ]; then
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "Running: ./deploy.sh $COMMAND $PARAMETER"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "# COMPOSER"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "Installing / Updateing dependencies."
    composer install -q --no-dev --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist
    composer dump-autoload
    echo "Dependencies installed/updated."
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "# NPM"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    if [ "$PARAMETER" = "--dev" ]; then
        echo "Installing/Updateing npm dependencies for development."
        npm install
    else
        echo "Installing/Updateing npm dependencies for production."
        npm install --production
    fi
    echo "Dependencies for npm have been installed/updated."
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "# Laravel / Artisan Setup"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    chmod -R 775 storage bootstrap/cache
    # php artisan migrate # Doing this later
    if [ "$COMMAND" = "init" ]; then
        php artisan key:generate
    fi
    if [ "$COMMAND" = "update" ]; then
        php artisan view:clear
        php artisan queue:flush
        # php artisan cache:clear # WE DONT DO THIS... THIS WILL KILL ALL ATCISS USER INPUT
        php artisan event:clear
        php artisan clear-compiled
        php artisan optimize
        php artisan route:clear # We need this here so our modules will be reachable
    fi
    php artisan migrate
    if [ "$COMMAND" = "init" ]; then
    php artisan db:seed
    fi
    echo "Laravel / Artisan setup completed."
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "# Asset Compile"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    # php artisan lang:js --no-lib # Create the localization js file
    if [ "$PARAMETER" = "--dev" ]; then
        npm run dev
    else
        npm run prod
    fi
    echo "Assets compiled."
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "# Booting"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    php artisan up
    echo "Application Booted."
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
    echo "# DONE"
    echo "# = = = = = = = = = = = = = = = = = = = = = = = = = = = ="
else
    php -r "copy('.env.example', '.env');"
    echo "It seems you are creating the application from scratch."
    echo "Therefore we have copied the .env.example file to .env!"
    echo "Before we can continue, please setup the .env file to your needs."
    echo "Once done just run the command ./deploy.sh $COMMAND $PARAMETER again."
fi