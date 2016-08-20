#!/usr/bin/env bash

[ "$1" == "--name" ] && {
    echo @api Coverage
    exit
}

find "$1" -name '*.php' -not -name '*Test.php' | \
    xargs grep -c --no-filename '@api' | awk '{s+=$1} END {print s}' 
