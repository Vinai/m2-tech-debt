# Magento 2 Module Technical Debt Aggregation

This is a meta extension that aggregates technical debt information on Magento 2 modules into a CSV format.

Each type of technical debt is a simple script which can be found in the `Collectors` directory.
The Collectors are executed for each module, passing the path to the module directory as the first argument.

Each collector also has to recognize the argument `--name`, which is used to build the column headers.

## Usage:

`$ bin/magento dev:technical-debt:collect`

By default all technical debt information is printed to stdout where it can be piped to a file or other tools.

Alternatively a file name can be specified, which will cause the CSV data to be written to that file.

`$ bin/magento dev:technical-debt:collect foo.csv`

## Intention

This module is supposed to be used as a tool to track the progress of the Magento core modules refactoring.
Each metric is not supposed to be used as absolute truth, but rather to get a feel for the general
direction the code is going. 
