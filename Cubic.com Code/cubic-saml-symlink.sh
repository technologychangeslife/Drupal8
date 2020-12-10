#!/bin/bash
# This script is intended for use from composer.json post-install and post-update

vendor_path="vendor/simplesamlphp/simplesamlphp"
saml_config_path="../../../saml_config"
links="
config
metadata
"

for link in $links
do
    # Check if directory exists as a real file
    if [ -e "$vendor_path/$link" ]; then
        if [ ! -L "$vendor_path/$link" ]; then
            rm -r "$vendor_path/$link"
        fi
    fi

    if [ ! -L "$vendor_path/$link" ]; then
        echo "Adding symlink for saml $link"
        ln -s "$saml_config_path/$link" "$vendor_path/$link"
    fi
done