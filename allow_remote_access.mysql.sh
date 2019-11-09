
#!/bin/bash

REPLACEMENT_VALUE=$(wget https://api.ipify.org/ -q -O -)

grep -qF 'bind-address=' $1 \
  || echo "bind-address=$REPLACEMENT_VALUE" >> $1

sed -i \
  -e "s/#* *bind-address=.*/bind-address=$REPLACEMENT_VALUE/"\
  -e "/skip-networking/s/^#*/#/" \
  $1