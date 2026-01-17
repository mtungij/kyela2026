# Cron Job Setup for Automatic Penalties

## Overview

The system automatically adds penalties to members who don't make payments on their scheduled days.

## Scheduled Task

-   **Command**: `penalties:add`
-   **Schedule**: Daily at 00:01 (12:01 AM)
-   **Timezone**: Africa/Dar_es_Salaam

## Setup Instructions

### 1. Add Laravel Scheduler to Cron

Edit your server's crontab:

```bash
crontab -e
```

Add this single line:

```bash
* * * * * cd /home/loan-pocket/kyela-mchezo-main && php artisan schedule:run >> /dev/null 2>&1
```

This runs every minute and Laravel's scheduler decides which tasks to execute.

### 2. Verify Scheduled Tasks

View all scheduled tasks:

```bash
php artisan schedule:list
```

### 3. Test the Command Manually

Run the penalty command manually to test:

```bash
php artisan penalties:add
```

### 4. Check Logs

Monitor Laravel logs for any errors:

```bash
tail -f storage/logs/laravel.log
```

## How It Works

1. **Daily at 00:01**: The command runs automatically
2. **Checks all collections**: With status pending, active, or partial
3. **Verifies payments**: Checks if member paid today
4. **Calculates penalty**: Based on member type (daily/weekly/monthly)
5. **Adds penalty**: Updates collection's total_penalty and penalty_balance

## Penalty Logic

-   **Daily members**: Penalized every day without payment
-   **Weekly members**: Penalized if 7+ days since last payment
-   **Monthly members**: Penalized if 30+ days since last payment

## Manual Execution

You can also run the command manually anytime:

```bash
php artisan penalties:add
```

## Troubleshooting

### Cron not running?

Check if cron service is active:

```bash
sudo systemctl status cron
```

### View cron logs:

```bash
grep CRON /var/log/syslog
```

### Permission issues?

Ensure the Laravel scheduler has proper permissions:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```
