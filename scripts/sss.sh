#!/usr/bin/env zsh

sss() {
  symfony version > /dev/null 2>&1
  if [ $? -ne 0 ]
  then
    echo "Le client symfony doit être installé"
    exit 1
  fi
  if [ "$#" -ne 1 ]
  then
    PROJECT_PATH="$PWD"
  else
    PROJECT_PATH=$1
  fi
  if [ -d "$PROJECT_PATH" ]
  then
    symfony server:stop --dir:$PROJECT_PATH
  else
    mkdir $PROJECT_PATH
    symfony server:stop --dir=$PROJECT_PATH
    rm -t $PROJECT_PATH
  fi
}
