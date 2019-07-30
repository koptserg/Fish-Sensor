#!/bin/sh

# mpu6050 | sed -e 's/yaw = //; s/pitch = //; s/roll = //; s/temperature = //; s/compass = 0.0, 0.0, 0.0//' > /tmp/mpu6050.txt
# mpu6050 | sed -e '= s/yaw = //; s/pitch = //; s/roll = //; s/temperature = //; s/compass = 0.0, 0.0, 0.0//'

# mpu6050 > /tmp/mpu6050.txt

CONF_FILE=/www/mpu6050
#FILE=/tmp/mpu6050.txt
#TMP_FILE=/tmp/mpu6050.tmp
#ntail=10
s=0
pdelta=0
delta=0
#cdelta=20

while [ $s -eq 0 ]
do

vars=$(cat $CONF_FILE) 
for i in $vars; do eval $i; done 
#echo "s = $s ntail = $ntail ndelta = $ndelta cdelta = $cdelta FILE = $FILE TMP_FILE = $TMP_FILE"
 

tail -$ntail $FILE | sed -e 's/yaw = //; s/pitch = //; s/roll = //; s/temperature = //; s/compass = 0.0, 0.0, 0.0//' > $TMP_FILE

n=1
#varm=$(awk 'BEGIN{FS="	"}{print $1*10}' < "$TMP_FILE" )
for var in $(awk 'BEGIN{FS="	"}{print $2*10}' < "$TMP_FILE" )
do
  if [ $n -eq 1 ]
  then
    var1=$var
  fi

  if [ $n -eq $ndelta ]
  then
    varn=$var
  fi

  if [ $var -ne 0 ]
  then
#      echo "var = $var"
      varn=$var
  fi

  let "n += 1"
done

if [ $var1 -lt 0 ]
  then
     var1=$(($var1*-1))
fi
if [ $varn -lt 0 ]
  then
     varn=$(($varn*-1))
fi

delta=$(($var1+$varn*-1))
if [ $delta -lt 0 ]
  then
    delta=$(($delta*-1))
fi

#  echo "n = $(($n-1)) varm1 = $var1 varn = $varn delta = $delta"

if [ $delta -ge $cdelta ] 
then
  echo 1 > /sys/class/gpio/gpio42/value
  if [ $delta -ne $pdelta ]
     then
#     echo "$(date) n = $(($n-1)) varm1 = $var1 varn = $varn delta = $delta pdelta = $pdelta"
     echo "$(date) $delta"
     pdelta=$delta
  fi 
else
  echo 0 > /sys/class/gpio/gpio42/value
fi

done

