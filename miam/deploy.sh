#!/bin/sh
# Usage: ./deploy.sh username@server
rsync --archive --force --delete --progress --compress --checksum --exclude-from=miam/config/rsync_exclude.txt -e ssh ./ $1:site/