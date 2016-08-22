# Collectors

Each collector is an executable script.  
It takes one argument - either `--name' or a directory path to a Magento 2 module.

If the `--name` argument is given, it should output the string for the title of the column.

If the directory path is given, it should output a string representing the metric value for the given module.

The collectors can be written in any language - bash, PHP, perl, C - it just has to be executable.

