# 🎰 Charitable Casino Platform

**Transform gaming into global good!**

A revolutionary free-to-play casino platform where players earn virtual currency (Forum Gold) through games and use it to support real-world charitable causes. Every spin, every bet, every win contributes to making the world a better place.

## ✨ Vision

This isn't just another casino - it's a platform that harnesses the engaging mechanics of casino gaming for positive social impact. Players enjoy their favorite games completely free, and their success translates into real donations to verified charitable organizations worldwide.

## 🎯 Core Features

### 🎮 Free-to-Play Casino Games
- **Slots** - Classic 3-reel slot machine with provably fair outcomes
- **Roulette** - European roulette with multiple betting options
- **Dice** - Predict over/under with customizable multipliers
- All games use provably fair algorithms for transparent, verifiable results

### 💰 Forum Gold System
- Virtual currency earned through gameplay
- Daily login bonuses with streak rewards
- Achievement-based rewards
- No real money gambling - 100% free to play
- Cannot be converted back to real money (prevents exploitation)

### 🌍 Charitable Donation System
8 Supported Cause Categories:
- 🌳 **Environment** - Tree planting, conservation
- 💧 **Clean Water** - Water filtration, well construction
- 🍞 **Food Banks** - Hunger relief, meal programs
- 👶 **Children** - Orphanages, child welfare
- 🏥 **Medical Aid** - Healthcare, medical supplies
- 📚 **Education** - Schools, literacy programs
- 🏠 **Housing** - Homeless shelters, housing support
- 🌍 **General** - Other verified causes

**How It Works:**
- Players convert Forum Gold to real donations
- Default rate: 10,000 Forum Gold = $1 USD
- Admin processes actual donations to partner organizations
- Users receive donation certificates
- Real-time impact tracking and transparency

### 🏆 Progression & Engagement
- **Level System** - Gain XP from playing, unlock new features
- **Daily Bonuses** - Streaks up to 30 days for massive rewards
- **Achievements** - Unlock badges and bonus gold
- **Leaderboards** - Compete in various categories
- **In-Game Shop** - Avatars, themes, boosts, VIP status

### 📊 Impact Dashboard
- Personal donation history
- Global impact statistics
- Real-time charitable metrics
- Donation certificates
- Contribution leaderboards

### 🔒 Security & Fairness
- Provably fair gaming with cryptographic verification
- Laravel Sanctum authentication
- Secure password hashing
- CSRF protection
- Input validation
- Bot detection & rate limiting

## 🛠️ Technical Stack

**Backend:**
- PHP 8.0+ / Laravel 8
- MySQL/MariaDB database
- Laravel Sanctum for API authentication
- Queue system for processing donations
- Caching support (Redis optional)

**Frontend:**
- Vue.js 2 for reactive components
- Bootstrap 4 for responsive design
- Chart.js for visualizations
- Laravel Mix for asset compilation

**Games:**
- HTML5 Canvas for game rendering
- Server-side validation for all bets
- Cryptographic provably fair system
- Real-time balance updates

## 📁 Project Structure

```
crypto-casino-builder/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── Game/GameController.php
│   │   │   ├── User/UserController.php
│   │   │   ├── User/DonationController.php
│   │   │   └── Admin/AdminController.php
│   │   └── Middleware/AdminMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Game.php
│   │   ├── GameSession.php
│   │   ├── ForumGoldTransaction.php
│   │   ├── CharitableCause.php
│   │   ├── Donation.php
│   │   ├── Achievement.php
│   │   ├── DailyBonus.php
│   │   ├── ShopItem.php
│   │   └── Leaderboard.php
│   └── Services/
│       ├── Game/
│       │   ├── SlotGameService.php
│       │   ├── RouletteGameService.php
│       │   └── DiceGameService.php
│       ├── ForumGold/ForumGoldService.php
│       ├── Donation/DonationService.php
│       └── Achievement/AchievementService.php
├── database/
│   ├── migrations/ (12 migration files)
│   └── seeders/
│       ├── GamesSeeder.php
│       ├── CharitableCausesSeeder.php
│       └── AchievementsSeeder.php
├── routes/
│   ├── api.php
│   └── web.php
├── .env.example
├── CHARITABLE_CASINO_ARCHITECTURE.md
├── DEPLOYMENT_GUIDE.md
└── README_PROJECT.md (this file)
```

## 🚀 Quick Start

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/crypto-casino-builder.git
cd crypto-casino-builder
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
Edit `.env` with your database credentials, then:
```bash
php artisan migrate
php artisan db:seed
```

5. **Build frontend assets**
```bash
npm run dev
# or for production
npm run production
```

6. **Start development server**
```bash
php artisan serve
```

Visit `http://localhost:8000`

## 📋 API Endpoints

### Public
- `POST /api/register` - Register new user
- `POST /api/login` - User login
- `GET /api/games` - List available games
- `GET /api/donations/global-impact` - Global donation stats

### Authenticated
- `POST /api/daily-bonus` - Claim daily login bonus
- `POST /api/games/slots/play` - Play slots
- `POST /api/games/roulette/play` - Play roulette
- `POST /api/games/dice/play` - Play dice
- `POST /api/donations/donate` - Make a donation
- `GET /api/dashboard` - User dashboard stats
- `GET /api/achievements` - User achievements
- `GET /api/leaderboard` - Global leaderboards

### Admin (requires admin middleware)
- `GET /api/admin/dashboard` - Admin statistics
- `POST /api/admin/donations/{id}/process` - Process donation
- `POST /api/admin/users/{id}/ban` - Ban user
- `POST /api/admin/causes` - Create charitable cause

## 📖 Documentation

- **[CHARITABLE_CASINO_ARCHITECTURE.md](CHARITABLE_CASINO_ARCHITECTURE.md)** - Complete system architecture
- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Hostinger deployment instructions

## 💡 How to Use

### For Players
1. Register for a free account (5,000 Forum Gold welcome bonus!)
2. Claim daily login bonus
3. Play casino games to earn more gold
4. Visit Donations page
5. Choose a charitable cause
6. Convert your Forum Gold to real donations
7. Track your impact on your dashboard

### For Admins
1. Access admin panel at `/admin`
2. Monitor pending donations
3. Process real-world donations to charities
4. Manage games, causes, and users
5. Track platform statistics

## 🎁 Default Configuration

**New User Bonus:** 5,000 Forum Gold
**Daily Login Bonus:** 1,000 - 20,000 (based on streak)
**Conversion Rate:** 10,000 Forum Gold = $1 USD
**Minimum Donation:** 1,000 Forum Gold

**Games:**
- Slots: Min bet 10, Max bet 10,000
- Roulette: Min bet 10, Max bet 5,000
- Dice: Min bet 10, Max bet 15,000

## 🌟 Success Metrics

**Platform Health:**
- Total active users
- Daily active users
- Games played per day
- User retention rate

**Charitable Impact:**
- Total donations processed
- Number of causes supported
- Real-world outcomes (trees planted, meals served, etc.)
- User satisfaction

**Economy:**
- Forum Gold in circulation
- Gold earned vs spent
- Donation conversion rate
- Platform sustainability

## 🔐 Security Best Practices

- Never commit `.env` file
- Use strong passwords for all accounts
- Enable HTTPS in production
- Regular database backups
- Monitor for suspicious activity
- Keep dependencies updated
- Implement rate limiting
- Validate all user inputs

## 🤝 Contributing

While this is a custom project, improvements are welcome:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

Built with:
- Laravel - PHP Framework
- Vue.js - Progressive JavaScript Framework
- Bootstrap - CSS Framework
- MySQL - Database

Partner Organizations (Example):
- One Tree Planted
- charity: water
- Feeding America
- SOS Children's Villages
- And many more!

## 📧 Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Check documentation files
- Contact: support@yourdomain.com

---

## 🌈 Making a Difference

Every game played, every donation made, every achievement unlocked contributes to real-world positive change. Together, we're proving that gaming can be a force for good.

**Join us in eliminating negativity from this planet, one game at a time!** 🎰🌍💝
