# Mac Ansible Playbook

The Web server (nginx), PHP and the services (MariaDB, PostgreSQL, Mailpit) are installed locally with Ansible.

All the management of the system (configuration, update, etc) goes through this tool which allows everyone to have a similar environment.

## Install

You must first clone the repository to setup the environment.

```shell
$ xcode-select --install
$ git clone https://github.com/Akollade/mac-playbook.git ~/.mac-playbook
# With SSH
$ git clone git@github.com:Akollade/mac-playbook.git ~/.mac-playbook
```

Then you have to configure the environment:

```shell
$ cd ~/.mac-playbook
$ touch config.yml
$ vim config.yml
```

You can look in the file `default.config.yml` to customize your environment.

Example :

```yaml
---
############
# SYSTEM   #
############

edit_dev_config_with: code

homebrew_cask_packages:
  - tableplus
  - iterm2
  - alfred
  - code

############
# dotfiles #
############

zsh_theme: agnoster

#########
# GIT   #
#########

git_user_name: John Doe
git_user_email: john@doe.fr

###########
# Nginx   #
###########

nginx_sites:
  - name: wallabag
    server_name: wallabag.localhost
    type: symfony
    root: "~/dev/wallabag/web"
    php_version: '8.0'
```

Homebrew is needed, see https://brew.sh/ to install it.

Then :

```shell
$ make bootstrap
$ make setup-mkcert
```

And to finish : reboot the computer.

From there, you can use the **dev** binary to manage the environment.

## Save password

To avoid having to enter the SUDO password all the time we will encrypt our SUDO password with a password that will be stored in the keychain :

```shell
$ ./scripts/generate-ansible-password
```

## Usages

To list all commands just type `dev`.

## License

[mac-playbook](https://github.com/Akollade/mac-playbook) is licensed under the [MIT license](LICENSE).
