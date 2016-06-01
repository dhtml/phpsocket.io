#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SCR="$DIR/server.php"

haySack=ps auxwww|grep -i 'server.php'

appName=server.php

if [ "${haySack/$appName}" = "$haySack" ] ; then
    echo "${appName} will be restarted now"
	nohup php $SCR > error_log &
else
  echo "${appName} is running properly"
fi