#!/usr/bin/env bash
ps aux | grep my_swoole_http_master | awk '{print $2}' | xargs kill -USR1