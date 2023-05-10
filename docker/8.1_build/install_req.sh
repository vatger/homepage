apt update \
    && apt install ca-certificates apt-transport-https software-properties-common wget curl lsb-release -y \
    && curl -sSL https://packages.sury.org/php/README.txt | bash -x \
    && apt update && apt upgrade -y \
    && apt install git nodejs npm php8.1 php8.1-fpm php8.1-mbstring php8.1-curl php8.1-dom redis curl composer -y