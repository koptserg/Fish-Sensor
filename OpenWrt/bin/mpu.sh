#!/bin/sh

FILENAME=/tmp/mpu6050.txt

FILENAME1=/tmp/temp6050.txt

tail -5 $FILENAME > $FILENAME1

#for proba in $(cut -d '=' -f1,2,3 $FILENAME1)
#do
#  echo $proba
#done

awk -F'	' '{ print $2 }' $FILENAME1