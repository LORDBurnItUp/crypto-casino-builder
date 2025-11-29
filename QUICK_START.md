# 🚀 Quick Start Guide

## What You Have

A complete **Charitable Casino Platform** with:

### ✅ **44 Backend Files Created**
- 11 Eloquent Models
- 12 Database Migrations
- 5 Controllers (Auth, Game, User, Donation, Admin)
- 6 Service Classes (Game logic, Donations, Achievements)
- 3 Seeders (Games, Causes, Achievements)
- Complete API routes
- All Laravel core files (Kernel, Middleware, Providers)

### 🎮 **3 Provably Fair Casino Games**
1. **Slots** 🎰 - 3-reel slot machine
2. **Roulette** 🎯 - European roulette
3. **Dice** 🎲 - Over/under prediction

### 💰 **Forum Gold System**
- Virtual currency (not real money)
- Daily login bonuses (1,000 - 20,000 gold)
- Achievement rewards
- Transaction history

### 🌍 **8 Charitable Causes**
- 🌳 Tree Planting
- 💧 Clean Water
- 🍞 Food Banks
- 👶 Orphanages
- 🌊 Ocean Cleanup
- 🏥 Medical Aid
- 📚 Education
- 🏠 Homeless Shelters

### 🏆 **8 Achievements**
- First Steps
- Game Enthusiast
- Gaming Legend
- First Good Deed
- Generous Soul
- Philanthropist
- Diverse Supporter
- Gold Collector

## 📦 What's Included

```
crypto-casino-builder/
├── app/
│   ├── Console/Kernel.php
│   ├── Exceptions/Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── Game/GameController.php
│   │   │   ├── User/UserController.php
│   │   │   ├── User/DonationController.php
│   │   │   └── Admin/AdminController.php
│   │   ├── Kernel.php
│   │   └── Middleware/ (8 middleware files)
│   ├── Models/ (11 models)
│   ├── Providers/ (5 providers)
│   └── Services/ (6 service classes)
├── database/
│   ├── migrations/ (12 migration files)
│   └── seeders/ (3 seeders)
├── routes/
│   └── api.php (Complete API)
└── Documentation/
    ├── CHARITABLE_CASINO_ARCHITECTURE.md
    ├── DEPLOYMENT_GUIDE.md
    └── README_PROJECT.md
```

## 🔧 Setup for Local Development

### Option 1: With MySQL/MariaDB

```bash
# 1. Configure database in .env
DB_CONNECTION=mysql
DB_DATABASE=charitable_casino
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 2. Run migrations
php artisan migrate

# 3. Seed database
php artisan db:seed

# 4. Start server
php artisan serve
```

### Option 2: With SQLite

```bash
# 1. Install SQLite PDO extension
sudo apt-get install php-sqlite3  # Ubuntu/Debian
# or
brew install php@8.4 --with-sqlite  # Mac

# 2. Configure .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# 3. Create database
touch database/database.sqlite

# 4. Run setup
php artisan migrate
php artisan db:seed
php artisan serve
```

## 🌐 Deployment to Hostinger

**Full guide:** `DEPLOYMENT_GUIDE.md`

### Quick Steps:
1. Upload files via FTP/File Manager
2. Create MySQL database in hPanel
3. Configure .env with database credentials
4. Run: `php artisan migrate --force`
5. Run: `php artisan db:seed --force`
6. Point domain to `/public` directory

## 📡 API Endpoints

### Authentication
```
POST /api/register         - Register new user
POST /api/login           - Login
POST /api/logout          - Logout
GET  /api/me              - Get current user
```

### Games
```
GET  /api/games                 - List all games
POST /api/games/slots/play      - Play slots
POST /api/games/roulette/play   - Play roulette
POST /api/games/dice/play       - Play dice
GET  /api/games/history         - Game history
```

### User
```
GET  /api/dashboard            - User dashboard & stats
POST /api/daily-bonus          - Claim daily bonus
GET  /api/achievements         - List achievements
POST /api/achievements/{id}/claim - Claim reward
GET  /api/leaderboard          - View leaderboards
```

### Donations
```
GET  /api/donations/causes         - List causes
POST /api/donations/donate         - Make donation
GET  /api/donations/my-donations   - User donations
GET  /api/donations/global-impact  - Global stats
```

## 🎮 Example API Usage

### Register User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "player1",
    "email": "player1@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Play Slots
```bash
curl -X POST http://localhost:8000/api/games/slots/play \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "bet_amount": 100
  }'
```

### Make Donation
```bash
curl -X POST http://localhost:8000/api/donations/donate \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "cause_id": 1,
    "forum_gold_amount": 10000
  }'
```

## 🎯 Key Features

### For Players
- ✅ 100% Free to play
- ✅ No real money gambling
- ✅ Daily login streak bonuses
- ✅ Unlock achievements
- ✅ Make real charitable impact
- ✅ Track donation history
- ✅ Compete on leaderboards

### For Admins
- ✅ User management
- ✅ Process donations
- ✅ Manage games & causes
- ✅ Platform statistics
- ✅ Economy controls

## 💡 How It Works

1. **Players register** → Get 5,000 Forum Gold welcome bonus
2. **Play casino games** → Earn more Forum Gold
3. **Claim daily bonus** → Build streak for bigger rewards
4. **Make donations** → 10,000 Gold = $1 to charity
5. **Track impact** → See real-world contribution

## 📊 Default Configuration

- **Welcome Bonus:** 5,000 Forum Gold
- **Daily Bonus Range:** 1,000 - 20,000 Gold
- **Conversion Rate:** 10,000 Gold = $1 USD
- **Min Donation:** 1,000 Gold ($0.10)
- **3 Games Ready:** Slots, Roulette, Dice
- **8 Causes Ready:** Environment, Water, Food, etc.

## 🔐 Security Features

- ✅ Laravel Sanctum authentication
- ✅ Provably fair gaming
- ✅ CSRF protection
- ✅ Input validation
- ✅ Secure password hashing
- ✅ Rate limiting
- ✅ SQL injection prevention

## 🚧 Troubleshooting

### "Could not find driver"
Install PHP database extension:
```bash
sudo apt-get install php-mysql php-sqlite3
# or
brew install php --with-mysql
```

### "Class not found"
Regenerate autoload:
```bash
composer dump-autoload
```

### Permission denied on storage
Fix permissions:
```bash
chmod -R 775 storage bootstrap/cache
```

## 📚 Documentation Files

1. **CHARITABLE_CASINO_ARCHITECTURE.md** - Complete system design
2. **DEPLOYMENT_GUIDE.md** - Hostinger deployment walkthrough
3. **README_PROJECT.md** - Full project documentation
4. **QUICK_START.md** - This file

## 🎉 You're Ready!

Your charitable casino platform is complete and ready to:
- 🎮 Provide free entertainment
- 💰 Manage virtual currency economy
- 🌍 Support real charitable causes
- 📊 Track global impact
- 🏆 Reward player engagement

**Make gaming a force for good!** 🚀🌍

---

Need help? Check the other documentation files or review the code comments.
