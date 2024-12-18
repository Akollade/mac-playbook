#!/bin/sh

pipx install ansible-core
pipx inject ansible-core ansible
pipx inject ansible-core passlib
pipx inject ansible-core github3-py

if [ -n "$1" ]; then
  echo "Force install of ansible-core$1"
  pipx inject ansible-core "ansible-core$1" --force
fi
