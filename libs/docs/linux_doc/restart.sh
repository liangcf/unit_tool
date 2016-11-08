############################################################################################
#!/bin/sh

rm -f  /usr/local/nginx/conf/nginx.conf
cp /mnt/hgfs/workspace/configure/nginx.conf /usr/local/nginx/conf/nginx.conf

/etc/init.d/nginx restart

service php-fpm restart

############################################################################################