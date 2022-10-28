#!/bin/sh
set -e

chgrp -R www-data bootstrap/cache storage
chmod -R ug+rwx bootstrap/cache
chmod -R ug+rw storage

exec "$@"
