#!/usr/bin/env bash

[ "$1" == "--name" ] && {
    echo Module Name
    exit
}

grep setup_version "$1/etc/module.xml" | cut -d\" -f2
