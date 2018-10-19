# Mac Ansible Playbook

The Web server (nginx), PHP and the services (MariaDB, Mailhog) are installed locally with Ansible.

All the management of the system (configuration, update, etc) goes through this tool which allows all the world to have a similar environment.

## Install

You must first clone the repository to setup the environment.

```shell
$ xcode-select --install
$ git clone https://github.com/ter-informatique/mac-playbook.git ~/.mac-playbook
# With SSH
$ git clone git@github.com:ter-informatique/mac-playbook.git ~/.mac-playbook
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

edit_dev_config_with: atom

homebrew_cask_packages:
- sequel
- iterm2
- alfred
- atom

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

```

Then :

```shell
$ make bootstrap
```

And to finish : reboot the computer.

From there, you can use the **dev** binary to manage the environment.

## Save password

To avoid having to enter the SUDO password all the time we will encrypt our SUDO password  with a password that will be stored in the keychain :

```shell
$ ./scripts/generate-ansible-password
```


## Usages

To list all commands just type `dev`.

## License

[mac-playbook](https://github.com/ter-informatique/mac-playbook) is licensed under the [MIT license](LICENSE).
