#!/usr/bin/env bash

while [ $# -ne 0 ]; do
    case "$1" in
        "--name")
            echo Testing Echo Collector
            exit 0
        ;;
        *)
            echo $1
        ;;
    esac
    shift
done
