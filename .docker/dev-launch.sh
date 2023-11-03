#!/bin/bash

composer install
tmux \
	new-session "setsid -w apache2ctl -DFOREGROUND" \; \
	split-window
