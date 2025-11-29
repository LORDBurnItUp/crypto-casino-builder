# Deployment Guide for Hostinger

This guide will help you deploy the Charitable Casino platform to your Hostinger hosting account.

## Prerequisites

- Hostinger Business Plan with:
  - MySQL database access
  - SSH access (recommended)
  - PHP 8.0 or higher
  - Composer access

## Step 1: Prepare Your Hostinger Environment

### 1.1 Create MySQL Database

1. Log in to your Hostinger control panel (hPanel)
2. Go to **Databases** → **MySQL Databases**
3. Click **Create New Database**
4. Note down:
   - Database name
   - Database username
   - Database password
   - Database host (usually `localhost`)

### 1.2 Access Your Domain

Make sure your domain is pointed to your Hostinger hosting:
1. Go to **Domains** in hPanel
2. Set up your domain or use the temporary Hostinger domain

## Step 2: Upload Files to Hostinger

### Option A: Using File Manager (Easy)

1. In hPanel, go to **Files** → **File Manager**
2. Navigate to `public_html` (or your domain's root folder)
3. Upload all project files:
   - Compress the project into a ZIP file first
   - Upload the ZIP via File Manager
   - Extract it in the `public_html` directory

### Option B: Using FTP (Recommended)

1. Get your FTP credentials from hPanel → **Files** → **FTP Accounts**
2. Use FileZilla or any FTP client to connect
3. Upload all project files to `public_html`

### Option C: Using Git (Advanced)

If you have SSH access:

```bash
ssh your_username@your_server_ip
cd public_html
git clone https://github.com/yourusername/crypto-casino-builder.git .
```

## Step 3: Configure Environment

### 3.1 Create .env File

1. Copy `.env.example` to `.env`
2. Edit the `.env` file with your database credentials:

```bash
# Via SSH
cp .env.example .env
nano .env

# Via File Manager
# Copy .env.example, rename to .env, then edit
```

### 3.2 Update .env with Your Details

```env
APP_NAME="Charitable Casino"
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database_name_here
DB_USERNAME=your_username_here
DB_PASSWORD=your_password_here

MAIL_HOST=smtp.hostinger.com
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
```

## Step 4: Install Dependencies

### Via SSH (Recommended)

```bash
cd public_html
composer install --optimize-autoloader --no-dev
npm install
npm run production
```

### Via Hostinger Terminal

Some Hostinger plans have a terminal in hPanel:
1. Go to **Advanced** → **Terminal**
2. Run the same commands above

## Step 5: Set Up Database

### 5.1 Generate Application Key

```bash
php artisan key:generate
```

### 5.2 Run Migrations

```bash
php artisan migrate --force
```

### 5.3 Seed Initial Data

```bash
php artisan db:seed --force
```

This will create:
- 3 casino games (Slots, Roulette, Dice)
- 8 charitable causes
- Achievement system

## Step 6: Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Or via File Manager:
1. Right-click `storage` folder → Permissions
2. Set to `775`
3. Repeat for `bootstrap/cache`

## Step 7: Configure Web Server

### For Apache (Hostinger default)

Create or edit `.htaccess` in your root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Point Document Root to /public

In hPanel:
1. Go to **Domains** → Your domain
2. Click **Manage**
3. Under **Document Root**, add `/public` to the path
4. Example: `/public_html/public`

## Step 8: Create Admin Account

### Via SSH/Terminal:

```bash
php artisan tinker
```

Then run:

```php
$admin = new App\Models\User();
$admin->username = 'admin';
$admin->email = 'admin@yourdomain.com';
$admin->password = Hash::make('your_secure_password');
$admin->forum_gold_balance = 1000000;
$admin->is_active = true;
$admin->save();
```

## Step 9: Test Your Installation

1. Visit `https://yourdomain.com`
2. Try registering a new account
3. Test the daily bonus
4. Play a game
5. Make a test donation

## Step 10: Optimization (Optional but Recommended)

### Cache Configuration

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Enable Maintenance Mode (for updates)

```bash
php artisan down
# Make your changes
php artisan up
```

## Common Issues & Solutions

### Issue 1: 500 Internal Server Error

**Solution**: Check file permissions and `.env` configuration

```bash
chmod -R 775 storage bootstrap/cache
php artisan config:clear
php artisan cache:clear
```

### Issue 2: Database Connection Failed

**Solution**: Verify database credentials in `.env`
- Double-check DB_HOST (usually `localhost`)
- Ensure database user has all privileges

### Issue 3: CSS/JS Not Loading

**Solution**: Run asset compilation

```bash
npm run production
php artisan storage:link
```

### Issue 4: Can't Access Admin Routes

**Solution**: Create admin middleware

Check that `app/Http/Middleware/AdminMiddleware.php` exists and is registered in `app/Http/Kernel.php`

## Security Checklist

- [ ] Change APP_KEY to a unique value
- [ ] Set APP_DEBUG=false in production
- [ ] Use strong database password
- [ ] Enable HTTPS/SSL certificate (free with Hostinger)
- [ ] Set proper file permissions (never 777)
- [ ] Keep `.env` file secure (never commit to git)
- [ ] Regular backups of database

## Scheduled Tasks (Cron Jobs)

Set up Laravel's scheduler in hPanel:

1. Go to **Advanced** → **Cron Jobs**
2. Add this command to run every minute:

```bash
cd /home/your_username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

## Backup Strategy

### Database Backup

In hPanel → Databases → phpMyAdmin:
1. Select your database
2. Click **Export**
3. Download SQL file
4. Store safely

### File Backup

Use hPanel's **Backups** feature or download via FTP regularly.

## Support & Maintenance

### Update Application

```bash
git pull origin main  # If using git
composer install --no-dev
php artisan migrate --force
php artisan config:cache
```

### Monitor Donations

Regularly check Admin Dashboard:
- Process pending donations
- Update charitable cause impact metrics
- Review user reports

## Going Live Checklist

- [ ] Database configured and migrated
- [ ] Games working correctly
- [ ] Donations system tested
- [ ] Email notifications working
- [ ] SSL certificate installed
- [ ] Domain properly configured
- [ ] Admin account created
- [ ] Regular backups scheduled
- [ ] Terms of Service added
- [ ] Privacy Policy added

## Need Help?

- Check Laravel docs: https://laravel.com/docs
- Hostinger support: https://www.hostinger.com/contact
- Project repository issues

---

**Congratulations!** Your charitable casino platform is now live and ready to make a positive impact on the world! 🎉🌍
