#!/bin/sh

# Ask for the administrator password upfront
sudo -v

echo "Install command line developer tools"
xcode-select --install

echo "Checking for Homebrew..."
if test ! $(which brew); then
  echo "Installing homebrew..."
  /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
fi

echo "Updating brew..."
brew update

echo "Installing Ansible..."
brew install python
pip3 install -r requirements.txt

echo "Provisioning with Ansible..."
make setup
