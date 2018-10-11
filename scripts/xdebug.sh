#!/bin/bash
# Thanks http://github.com/w00fz/xdebug-osx

command="$1"

php_version=$(php -r "\$v=explode('.', phpversion() ); echo implode('.', array_splice(\$v, 0, -1));")

xdebug_conf_path="/usr/local/etc/php/$php_version/conf.d"
xdebug_conf_file="ext-xdebug.ini"
xdebug_conf=$xdebug_conf_path/$xdebug_conf_file

phpPackage="php@$php_version"
pecl info xdebug 2> /dev/null > /dev/null

if [ $? -eq 0 ]; then
    if [ ! -f "$xdebug_conf" ] && [ ! -f "$xdebug_conf.disabled" ]; then
        echo ""
        echo "The ini file for Xdebug was not found at '$xdebug_conf_path'"
        echo "Did you install Xdebug via Homebrew?"
        echo ""

        exit 1
    else
        STATUS="enabled"
        if [ -f "$xdebug_conf" ] && [ -f "$xdebug_conf.disabled" ]; then
            echo ""
            echo "Detected both enabled and disabled Xdebug ini files. Deleting the disabled one."
            echo ""

            rm -rf "$xdebug_conf.disabled"
            STATUS="enabled"
        elif [ -f "$xdebug_conf.disabled" ]; then
            STATUS="disabled"
        fi

        if [ $# -eq 1 ] && [ "$1" == "on" ] || [ "$1" == "off" ]; then
            if [ "$command" == "on" ]; then
                mv "$xdebug_conf.disabled" "$xdebug_conf" 2> /dev/null
                STATUS="enabled"
            elif [ "$command" == "off" ]; then
                mv "$xdebug_conf" "$xdebug_conf.disabled" 2> /dev/null
                STATUS="disabled"
            fi

            echo ""
            echo "Xdebug has been $STATUS, restarting php-fpm"

            brew services restart $phpPackage 2> /dev/null > /dev/null
        else
            echo ""
            echo "Usage: dev xdebug-on or dev xdebug-off"
        fi

        echo ""
        echo "You are running PHP v$php_version with Xdebug $STATUS"
    fi
else
    echo ""
    echo "Xdebug for PHP $php_version was never installed or not installed via Brew."
    echo ""
    exit 1
fi
