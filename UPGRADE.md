# UPGRADE

## PostgreSQL 12

```
brew uninstall postgresql@11
brew uninstall --ignore-dependencies postgresql@12
rm -rf /usr/local/var/postgresql@11
rm -rf /usr/local/var/postgresql@12
rm -rf /usr/local/var/postgres
dev setup
dev fix-postgresql-locale
```
