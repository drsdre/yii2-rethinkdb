#!/bin/sh -e
#
# install rethinkdb

if (php --version | grep -i HipHop > /dev/null); then
  echo "rethinkdb does not work on HHVM currently, skipping"
  exit 0
else
  echo "extension = rethink.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
fi

echo "RethinkDB Server version:"
rethinkd --version

echo "RethinkDB PHP Extension version:"
php -i |grep rethink -4 |grep -2 Version

# enable text search
rethink --eval 'db.adminCommand( { setParameter: true, textSearchEnabled : true})'

cat /etc/rethinkdb.conf
