#!/usr/bin/env bash
BIN_TARGET="`pwd`/vendor/phpunit/phpunit/phpunit"
"$BIN_TARGET" --configuration phpunit.local.xml
