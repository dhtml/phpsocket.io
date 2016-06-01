#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SCR="$DIR/testwebsock.php"

nohup php $SCR > error_log &
