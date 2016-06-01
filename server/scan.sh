#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SCR="$DIR/server.php"


if (ps aux | grep php | grep ${SCR} | grep -v grep)
then
      echo RUNNING
else
      echo STOPPED
fi