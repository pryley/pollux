#!/usr/bin/env bash
# usage: travis.sh before|after

if [ $1 == 'before' ]; then

	composer self-update

	# install php-coveralls to send coverage info
	composer init --require=satooshi/php-coveralls:0.7.0 -n
	composer install --no-interaction --ignore-platform-reqs

elif [ $1 == 'after' ]; then
  if [ "$TRAVIS_PHP_VERSION" != "7.0" ] && [ "$TRAVIS_PHP_VERSION" != "hhvm" ]; then
    wget https://scrutinizer-ci.com/ocular.phar
    php ocular.phar code-coverage:upload --format=php-clover ./tmp/clover.xml
  fi
fi
