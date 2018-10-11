#!/bin/sh

# Ask for the administrator password upfront
sudo -v

echo "Install command line developer tools"
xcode-select --install

echo "Checking for Homebrew..."
if test ! $(which brew); then
  echo "Installing homebrew..."
  ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
fi

echo "Updating brew..."
brew update

echo "Installing Ansible..."
brew install python
pip3 install ansible
pip3 install dnspython

echo "Provisioning with Ansible..."
make setup
