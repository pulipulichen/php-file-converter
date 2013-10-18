#!/bin/bash

cd uploaded
/usr/bin/pdf2htmlEX $1 $2
mv $2 ../completed/