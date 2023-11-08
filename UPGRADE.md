# UPGRADE

## PostgreSQL 12

```
brew uninstall --force postgresql@11
brew uninstall --ignore-dependencies postgresql@12
rm -rf $(brew --prefix)/var/postgresql@11
rm -rf $(brew --prefix)/var/postgresql@12
rm -rf $(brew --prefix)/var/postgres
dev setup tags="postgresql"
dev fix-postgresql-locale
```
