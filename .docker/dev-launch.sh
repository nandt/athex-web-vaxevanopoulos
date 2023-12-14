#!/bin/bash

if [ ! -f /version ] || [ "$(cat /version)" != "$(cat ./.docker/imgs-version)" ]; then
	echo -e "\n⚠  Dockerfile(s) updated, please rebuild both base and dev images\n\n"
	exit 1
fi

composer install
tmux \
	new-session "setsid -w apache2ctl -DFOREGROUND" \; \
	split-window
