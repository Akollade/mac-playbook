#!/bin/sh

pipx install ansible-core --force
pipx inject ansible-core ansible --force
pipx inject ansible-core passlib --force
pipx inject ansible-core github3-py --force

if [ -n "$1" ]; then
  echo "Force install of ansible-core$1"
  pipx inject ansible-core "ansible-core$1" --force
fi
