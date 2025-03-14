#! /bin/bash
set -e -x

# shellcheck disable=SC2034
TOP_DIR="$(dirname "$(realpath "${BASH_SOURCE[0]}")")"

# CENTOS/REDHAT/FEDORA WEBBASE=/var/www/html APACHEUSER=apache
WEBBASE=/var/www
APACHEUSER=www-data
UPLOAD=${WEBBASE}/web/OJ/upload
DATA=/var/hznuoj/data
STATIC_PHP=${WEBBASE}/web/OJ/include/static.php

# Install dependencies
apt-get clean
apt-get update
apt-get dist-upgrade -y
apt-get install -y gnupg ca-certificates wget curl

# Key: PHP repo
apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 4F4EA0AAE5267A6C

echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu focal main" >/etc/apt/sources.list.d/php.list

deps="apache2 php7.0 libapache2-mod-php7.0 php7.0-mysql php7.0-mbstring php7.0-gd php7.0-cli php-xml curl libcurl4 libcurl4-openssl-dev php7.0-curl"

apt-get update
# shellcheck disable=SC2086
apt-get -y install ${deps}
apt-get clean

cp -a "${TOP_DIR}"/../web ${WEBBASE}/

for dir in ${UPLOAD} ${DATA}; do
    if [[ ! -d "${dir}" ]]; then
        mkdir -p "${dir}"
    fi
done

if [[ ! -f ${STATIC_PHP} ]]; then
    touch ${STATIC_PHP}
fi

chown -R ${APACHEUSER} ${DATA}
chown -R ${APACHEUSER} ${UPLOAD}

cp ${WEBBASE}/web/OJ/include/static.example.php ${WEBBASE}/web/OJ/include/static.php

# change apache server root to /var/www/web
sed -i -e 's/\/var\/www\/html/\/var\/www\/web/g' /etc/apache2/sites-available/000-default.conf

# forbid directory access
sed -i -e 's/Options Indexes FollowSymLinks/Options FollowSymLinks/g' /etc/apache2/apache2.conf

# set limit of prefork mode
cat "${TOP_DIR}/../etc/apache2/mods-available/mpm_prefork.conf" >/etc/apache2/mods-available/mpm_prefork.conf
