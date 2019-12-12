#!/bin/sh
# Find PHP sources and syntax check them (lint)

ERRCNT=0

for src in *.php; do
  php -l ${src}
  if [ $? != 0 ]; then
    ERRCNT=$((ERRCNT + 1))
  fi
done

exit ${ERRCNT}

