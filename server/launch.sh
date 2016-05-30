#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SCR="$DIR/websocket.php"

haySack=ps auxwww|grep -i 'websocket.php'

appName=websocket.php

if [ "${haySack/$appName}" = "$haySack" ] ; then
    echo "${appName} will be restarted now"
	nohup php $SCR > error_log &
else
  echo "${appName} is running properly"
fi