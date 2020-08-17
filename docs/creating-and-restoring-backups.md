# Creating and restoring backups

There are two scripts for export and import backups.

- [bin/backup/create.sh](/bin/backup/create.sh)
- [bin/backup/restore.sh](/bin/backup/restore.sh) 

These scripts can be called manually or by cron.

Project has a `backup` directory with a one file `.gitignore` for ignoring everything in this directory except that file.

Backup is an archive with all project's data which required to be stored:

- Dump of MySQL database
- Dump of Redis database
- project's `www/.env` file
- complete `www/upload` directory
- ignored config files from `www/application/config` directory:
  - `social.php`
  - `email.php`
  - `communities.php`
  
Also script backups these two files:

- docker's `.env` file
- `www/application/config/database.php` 

But they are not using for restoring because of different root's passwords fom MySQL.

## Create backup (export data)

When you create a new backup there will be recreated a directory named `last-backup` (with unarchived data) and a new archive.

Archive has a date and time stamp in the name: `backup_2020-08-13_18-32-50.tar.gz`

To create a new backup just run:

```bash
./bin/backup/create.sh
```

## Restore backup (import data)

For restoring you can use the following command. Project's docker containers must be started.

```bash
./bin/backup/restore.sh <path-to-backup-archive-file>
```

For example

```bash
./bin/backup/restore.sh ./backup/backup_2020-08-13_18-32-50.tar.gz
```

All files will be put to their correct locations. Redis and Memcached containers will be restarted.