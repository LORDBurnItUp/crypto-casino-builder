#!/bin/bash

echo "🎮 CHARITABLE CASINO - QUICK START DEMO"
echo "======================================="
echo ""

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}Starting Laravel development server...${NC}"
echo ""
echo -e "${GREEN}🌐 Server running at: http://localhost:8000${NC}"
echo ""
echo -e "${YELLOW}Available API Endpoints:${NC}"
echo ""
echo "Authentication:"
echo "  POST   /api/register"
echo "  POST   /api/login"
echo "  POST   /api/logout"
echo ""
echo "Games:"
echo "  GET    /api/games"
echo "  POST   /api/games/slots/play"
echo "  POST   /api/games/roulette/play"
echo "  POST   /api/games/dice/play"
echo ""
echo "User:"
echo "  GET    /api/dashboard"
echo "  POST   /api/daily-bonus"
echo "  GET    /api/achievements"
echo ""
echo "Donations:"
echo "  GET    /api/donations/causes"
echo "  POST   /api/donations/donate"
echo "  GET    /api/donations/global-impact"
echo ""
echo -e "${BLUE}Press Ctrl+C to stop the server${NC}"
echo ""
echo "─────────────────────────────────────────"
echo ""

php artisan serve
