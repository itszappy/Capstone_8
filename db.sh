#!/bin/sh

# Change DBPASSWORD, DBUSER, DBHOST and DBNAME to match the values
# your mysql_db_info file on the webdev server.
mysql --password='Z4H7yorrKWW3' --user='hourcade' --host='dbdev.cs.uiowa.edu' 'db_hourcade'
