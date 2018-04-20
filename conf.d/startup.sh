#!/bin/sh
# startup.sh
/usr/bin/supervisord
pm2 start Node/server.js
supervisord -c /etc/supervisor/supervisord.conf
supervisorctl -c /etc/supervisor/supervisord.conf
