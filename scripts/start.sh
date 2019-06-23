#!/bin/sh

ln -f -s /var/app/target/todo.jar /etc/init.d/todo
service todo start