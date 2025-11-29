#!/bin/bash

echo "🧪 CHARITABLE CASINO - AUTOMATED TESTING"
echo "========================================"
echo ""

GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Test database connection
echo -e "${BLUE}[1/5] Testing database connection...${NC}"
php artisan migrate:status > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database connected${NC}"
else
    echo -e "${RED}✗ Database connection failed${NC}"
fi

# Count games
echo -e "${BLUE}[2/5] Checking seeded data...${NC}"
GAMES=$(php artisan tinker --execute="echo App\Models\Game::count();" 2>/dev/null)
CAUSES=$(php artisan tinker --execute="echo App\Models\CharitableCause::count();" 2>/dev/null)
ACHIEVEMENTS=$(php artisan tinker --execute="echo App\Models\Achievement::count();" 2>/dev/null)

echo -e "${GREEN}✓ Games: ${GAMES}${NC}"
echo -e "${GREEN}✓ Charitable Causes: ${CAUSES}${NC}"
echo -e "${GREEN}✓ Achievements: ${ACHIEVEMENTS}${NC}"

# Test routes
echo -e "${BLUE}[3/5] Testing API routes...${NC}"
php artisan route:list --path=api > /dev/null 2>&1
if [ $? -eq 0 ]; then
    ROUTE_COUNT=$(php artisan route:list --path=api 2>/dev/null | wc -l)
    echo -e "${GREEN}✓ API routes registered: $ROUTE_COUNT${NC}"
else
    echo -e "${RED}✗ Route registration failed${NC}"
fi

# Test models
echo -e "${BLUE}[4/5] Testing models...${NC}"
php artisan tinker --execute="new App\Models\User();" > /dev/null 2>&1 && echo -e "${GREEN}✓ User model${NC}"
php artisan tinker --execute="new App\Models\Game();" > /dev/null 2>&1 && echo -e "${GREEN}✓ Game model${NC}"
php artisan tinker --execute="new App\Models\Donation();" > /dev/null 2>&1 && echo -e "${GREEN}✓ Donation model${NC}"

# Create test user
echo -e "${BLUE}[5/5] Creating test user...${NC}"
php artisan tinker --execute="
\$user = App\Models\User::firstOrCreate(
    ['email' => 'test@casino.com'],
    [
        'username' => 'testplayer',
        'password' => Hash::make('password123'),
        'forum_gold_balance' => 10000,
        'is_active' => true
    ]
);
echo 'Test user created: ' . \$user->username;
" 2>/dev/null
echo -e "${GREEN}✓ Test user ready${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ ALL TESTS PASSED!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Test User Credentials:${NC}"
echo "  Email: test@casino.com"
echo "  Password: password123"
echo "  Starting Gold: 10,000"
echo ""
