#!/usr/bin/env bash

[ "$1" == "--name" ] && {
    echo Suppressed Warnings
    exit
}

find "$1" -name '*.php' -or -name '*.phtml' -not -name '*Test.php' | \
    xargs grep -c --no-filename '@SuppressWarnings' | awk '{s+=$1} END {print s}' 
