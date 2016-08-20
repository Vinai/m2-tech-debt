#!/usr/bin/env bash

[ "$1" == "--name" ] && {
    echo Usage of instanceof
    exit
}

find "$1" -name '*.php' -not -name '*Test.php' | \
    xargs grep -qi instanceof && echo "yes" || echo "no" 
