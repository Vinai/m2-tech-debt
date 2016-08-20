#!/usr/bin/env bash

[ "$1" == "--name" ] && {
    echo Direct ObjectManager Usage
    exit
}

find "$1" -name '*.php' -not -name '*Test.php' | \
    xargs grep -q "ObjectManager \|ObjectManager::getInstance" && echo "yes" || echo "no" 
