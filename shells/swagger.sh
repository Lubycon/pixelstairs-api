#! /bin/bash
echo Swagger Document Updaing.....
./vendor/bin/swagger ./app/Http
echo Copy vendor to root swagger.json file
rm -rf ./public/docs/swagger.json
echo Remove current json document
mv ./swagger.json ./public/docs/swagger.json
echo Move copied swagger.json file :: path = ./public/docs/swagger.json
