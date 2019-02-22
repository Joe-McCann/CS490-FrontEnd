#!/bin/bash
for file in *.*; do 
    cp $file /var/www/html
    cp $file /mnt/d/Coding_Files/Github/CS490-FrontEnd
done
