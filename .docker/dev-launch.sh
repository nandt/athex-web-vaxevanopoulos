#!/bin/bash

composer install
apache2ctl -D FOREGROUND
# apache2ctl start
# tmux \
# 	new-session "tail -f /var/log/apache2/error.log" \; \
# 	split-window
