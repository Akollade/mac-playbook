ANSIBLE_WITH_PASSWORD_COMMAND = ansible-playbook -c local -i inventory --ask-become-pass
ANSIBLE_WITHOUT_PASSWORD_COMMAND = ansible-playbook -c local -i inventory_sudo_password
ANSIBLE_COMMAND := $(if $(shell grep "sudo_password: !vault" config.yml),$(ANSIBLE_WITHOUT_PASSWORD_COMMAND),$(ANSIBLE_WITH_PASSWORD_COMMAND))
CONFIG_EDITOR = $(or $(DEV_CONFIG_EDITOR) , vim)

ANSIBLE_PLAYBOOK_SETUP=$(ANSIBLE_COMMAND) playbooks/setup.yml
ANSIBLE_PLAYBOOK_MANAGE_SERVICES=$(ANSIBLE_COMMAND) playbooks/manage_services.yml
tags = all

.DEFAULT_GOAL := help
default: help

.PHONY: help
help:
	@echo "mac-playbook"
	@echo ""
	@echo "Dashboard : http://localhost"
	@echo "New issue : https://github.com/TheGrowingPlant/mac-playbook/issues/new"
	@echo ""
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: tags
tags: ## List all tags
	@$(ANSIBLE_PLAYBOOK_SETUP) --list-tags

.PHONY: bootstrap
bootstrap: ## Bootstrap the dev environment for the first time
	@scripts/bootstrap.sh

.PHONY: setup
setup: ## Setup the dev environment
	@$(ANSIBLE_PLAYBOOK_SETUP) --tags=$(tags)

.PHONY: upgrade
upgrade: ## Upgrade of the apps and dev environment
	@echo "Upgrade apps"
	@echo ""
	@brew cu -y --cleanup
	@echo ""
	@echo "Upgrade of the dev environment"
	@echo ""
	@$(ANSIBLE_PLAYBOOK_SETUP) --extra-vars='upgrade_all_packages=true' --tags=$(tags)

.PHONY: setup-nginx
setup-nginx: ## Setup/config nginx
	@$(ANSIBLE_PLAYBOOK_SETUP) --tags="nginx"

.PHONY: setup-mkcert
setup-mkcert: ## Setup mkcert
	mkcert -install

.PHONY: dotfiles
dotfiles: ## Setup "dotfiles"
	@$(ANSIBLE_PLAYBOOK_SETUP) --tags="dotfiles"

.PHONY: config
config: ## Edit config
	@$(CONFIG_EDITOR) config.yml

.PHONY: update
update: ## Pull the last version of the dev environment
	@git pull origin master

.PHONY: stop
stop: ## Stop all services
	@$(ANSIBLE_PLAYBOOK_MANAGE_SERVICES) --extra-vars='service_state=stop'

.PHONY: start
start: ## Start all services
	@$(ANSIBLE_PLAYBOOK_MANAGE_SERVICES) --extra-vars='service_state=start'

.PHONY: restart
restart: ## Restart all services
	@$(ANSIBLE_PLAYBOOK_MANAGE_SERVICES) --extra-vars='service_state=restart'

.PHONY: status
status: ## List all services
	@brew services list

.PHONY: xdebug-on
xdebug-on: ## Enable XDebug
	@scripts/xdebug.sh on

.PHONY: xdebug-off
xdebug-off: ## Disable XDebug
	@scripts/xdebug.sh off

.PHONY: fix-python-crash
fix-python-crash: ## Fix Python with macOS Catalina, see https://github.com/TheGrowingPlant/mac-playbook/issues/53
	@brew install openssl
	@rm /usr/local/lib/libcrypto.dylib || true
	@ln -s /usr/local/opt/openssl@1.1/lib/libcrypto.1.1.dylib /usr/local/lib/libcrypto.dylib
	@rm  /usr/local/opt/openssl/lib/libcrypto.1.0.0.dylib || true
	@ln -s /usr/local/opt/openssl@1.1/lib/libcrypto.1.1.dylib /usr/local/opt/openssl/lib/libcrypto.1.0.0.dylib
	@rm /usr/local/lib/libssl.dylib || true
	@ln -s /usr/local/opt/openssl@1.1/lib/libssl.1.1.dylib /usr/local/lib/libssl.dylib
	@rm /usr/local/opt/openssl/lib/libssl.1.0.0.dylib || true
	@ln -s /usr/local/opt/openssl@1.1/lib/libssl.1.1.dylib /usr/local/opt/openssl/lib/libssl.1.0.0.dylib

.PHONY: fix-postgresql-locale
fix-postgresql-locale: ## Change default PostgreSQL server locale to en_US.UTF-8
	@rm -rf /usr/local/var/postgresql@11 || true
	@rm -rf /usr/local/var/postgresql@12 || true
	@rm -rf /usr/local/var/postgres || true
	@initdb -E UTF-8 --locale=en_US.UTF-8 /usr/local/var/postgres
	@$(ANSIBLE_PLAYBOOK_SETUP) tags "postgresql"
