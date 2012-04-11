#!/bin/bash
#
# fullsitebackup.sh V1.0
#
dbhost=localhost
dbuser=root
dbpass='wf@csu4ever'
dbname=rubyer
webrootdir=/home/sse/rubyer/
tempdir=/home/sse/rubyer_backup
tarnamebase=RubyerMeBackup
email=songshaoyin@qq.com
confdir=/home/sse/rubyer/wp-content/plugins/wordpress-ez-backup/functions/functions.sh
attach=yes
. $confdir