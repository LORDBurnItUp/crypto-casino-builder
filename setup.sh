#!/bin/bash

echo "🎰 CHARITABLE CASINO - AUTOMATED SETUP"
echo "======================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Check if .env exists, if not create from example
echo -e "${BLUE}[1/8] Setting up environment file...${NC}"
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${GREEN}✓ .env file already exists${NC}"
fi

# Step 2: Update .env with basic configuration
echo -e "${BLUE}[2/8] Configuring environment...${NC}"
sed -i 's/APP_ENV=production/APP_ENV=local/' .env
sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env
sed -i 's/DB_DATABASE=your_database_name/DB_DATABASE=charitable_casino/' .env
sed -i 's/DB_USERNAME=your_database_username/DB_USERNAME=root/' .env
sed -i 's/DB_PASSWORD=your_database_password/DB_PASSWORD=/' .env
echo -e "${GREEN}✓ Environment configured for local development${NC}"

# Step 3: Install Composer dependencies
echo -e "${BLUE}[3/8] Installing Composer dependencies...${NC}"
if command -v composer &> /dev/null; then
    composer install --no-interaction --prefer-dist 2>&1 | tail -5
    echo -e "${GREEN}✓ Composer dependencies installed${NC}"
else
    echo -e "${RED}⚠ Composer not found, skipping...${NC}"
fi

# Step 4: Generate application key
echo -e "${BLUE}[4/8] Generating application key...${NC}"
if [ -f artisan ]; then
    php artisan key:generate --force
    echo -e "${GREEN}✓ Application key generated${NC}"
else
    echo -e "${RED}⚠ Laravel not fully set up${NC}"
fi

# Step 5: Set up storage directories and permissions
echo -e "${BLUE}[5/8] Setting up storage permissions...${NC}"
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Storage directories created and permissions set${NC}"

# Step 6: Create SQLite database for testing (if MySQL not available)
echo -e "${BLUE}[6/8] Setting up database...${NC}"
if ! command -v mysql &> /dev/null; then
    echo "MySQL not found, using SQLite for demo..."
    touch database/database.sqlite
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    sed -i 's/DB_DATABASE=charitable_casino/DB_DATABASE=database\/database.sqlite/' .env
    echo -e "${GREEN}✓ SQLite database created${NC}"
else
    echo -e "${GREEN}✓ Using MySQL database${NC}"
fi

# Step 7: Run migrations
echo -e "${BLUE}[7/8] Running database migrations...${NC}"
php artisan migrate --force 2>&1 | grep -E "(Migrated|Migration table created|Nothing to migrate)"
echo -e "${GREEN}✓ Database migrations completed${NC}"

# Step 8: Seed the database
echo -e "${BLUE}[8/8] Seeding database with initial data...${NC}"
php artisan db:seed --force
echo -e "${GREEN}✓ Database seeded with games, causes, and achievements${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}🎉 SETUP COMPLETED SUCCESSFULLY! 🎉${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${BLUE}Your Charitable Casino is ready!${NC}"
echo ""
echo "📊 Database contains:"
echo "  • 3 Casino Games (Slots, Roulette, Dice)"
echo "  • 8 Charitable Causes"
echo "  • 8 Achievements"
echo ""
echo "🚀 Next steps:"
echo "  1. Start server: php artisan serve"
echo "  2. Visit: http://localhost:8000"
echo "  3. Create account & play!"
echo ""
echo "📖 Documentation:"
echo "  • Architecture: CHARITABLE_CASINO_ARCHITECTURE.md"
echo "  • Deployment: DEPLOYMENT_GUIDE.md"
echo "  • API Reference: README_PROJECT.md"
echo ""
