#!/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

mkdir -p $DIR/../storage/{app,framework,logs}
mkdir -p $DIR/../storage/framework/sessions

touch $DIR/../storage/development.db
