ANSIBLE_WITH_PASSWORD_COMMAND = ansible-playbook -c local -i inventory --ask-become-pass
ANSIBLE_WITHOUT_PASSWORD_COMMAND = ansible-playbook -c local -i inventory_sudo_password
ANSIBLE_COMMAND := $(if $(shell grep "sudo_password: !vault" config.yml),$(ANSIBLE_WITHOUT_PASSWORD_COMMAND),$(ANSIBLE_WITH_PASSWORD_COMMAND))
CONFIG_EDITOR = $(or $(DEV_CONFIG_EDITOR) , vim)

.DEFAULT_GOAL := help
default: help

.PHONY: help
help:
	@echo "mac-playbook"
	@echo ""
	@echo "Dashboard : http://localhost"
	@echo "New issue : https://github.com/ter-informatique/mac-playbook/issues/new"
	@echo ""
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: bootstrap
bootstrap: ## Bootstrap the dev environment for the first time
	@scripts/bootstrap.sh

.PHONY: setup
setup: ## Setup the dev environment
	@$(ANSIBLE_COMMAND) playbooks/setup.yml --extra-vars='upgrade_all_packages=false'

.PHONY: upgrade
upgrade: ## Upgrade of the apps and dev environment
	@echo "Upgrade apps"
	@echo ""
	@brew cu -y --cleanup
	@echo ""
	@echo "Upgrade of the dev environment"
	@echo ""
	@$(ANSIBLE_COMMAND) playbooks/setup.yml --extra-vars='upgrade_all_packages=true'

.PHONY: setup-nginx
setup-nginx: ## Setup/config nginx
	@$(ANSIBLE_COMMAND) playbooks/setup_nginx.yml

.PHONY: reset-ssl-certs
reset-ssl-certs: ## Force the regeneration of SSL certificates
	@$(ANSIBLE_COMMAND) playbooks/setup_webserver.yml --extra-vars='reset_ssl_certs=true'

.PHONY: dotfiles
dotfiles: ## Setup "dotfiles"
	@$(ANSIBLE_COMMAND) playbooks/dotfiles.yml

.PHONY: config
config: ## Edit config
	@$(CONFIG_EDITOR) config.yml

.PHONY: update
update: ## Pull the last version of the dev environment
	@git pull origin master

.PHONY: stop
stop: ## Stop all services
	@$(ANSIBLE_COMMAND) playbooks/manage_services.yml --extra-vars='service_state=stop'

.PHONY: start
start: ## Start all services
	@$(ANSIBLE_COMMAND) playbooks/manage_services.yml --extra-vars='service_state=start'

.PHONY: restart
restart: ## Restart all services
	@$(ANSIBLE_COMMAND) playbooks/manage_services.yml --extra-vars='service_state=restart'

.PHONY: status
status: ## List all services
	@brew services list

.PHONY: xdebug-on
xdebug-on: ## Enable XDebug
	@scripts/xdebug.sh on

.PHONY: xdebug-off
xdebug-off: ## Disable XDebug
	@scripts/xdebug.sh off
