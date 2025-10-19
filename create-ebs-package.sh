#!/bin/bash
# AWS Elastic Beanstalk Deployment Package Creator
# JV Prophecy Manager

set -e  # Exit on error

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}AWS Elastic Beanstalk Package Creator${NC}"
echo -e "${BLUE}JV Prophecy Manager${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""

# Package name with timestamp
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
PACKAGE_NAME="jv-prophecy-ebs-${TIMESTAMP}.zip"

echo -e "${YELLOW}Creating deployment package: ${PACKAGE_NAME}${NC}"
echo ""

# Step 1: Clean up old packages
echo -e "${YELLOW}Step 1/8: Cleaning up old packages...${NC}"
rm -f jv-prophecy-ebs-*.zip
echo -e "${GREEN}✓ Cleaned up old packages${NC}"
echo ""

# Step 2: Install production dependencies
echo -e "${YELLOW}Step 2/8: Installing production dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

# Step 3: Clear Laravel caches
echo -e "${YELLOW}Step 3/8: Clearing Laravel caches...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

# Step 4: Create temporary directory
echo -e "${YELLOW}Step 4/8: Creating temporary build directory...${NC}"
BUILD_DIR="build-temp-$$"
mkdir -p ${BUILD_DIR}
echo -e "${GREEN}✓ Temporary directory created: ${BUILD_DIR}${NC}"
echo ""

# Step 5: Copy necessary files
echo -e "${YELLOW}Step 5/8: Copying application files...${NC}"

# Core application files
cp -r app ${BUILD_DIR}/
cp -r bootstrap ${BUILD_DIR}/
cp -r config ${BUILD_DIR}/
cp -r database ${BUILD_DIR}/
cp -r public ${BUILD_DIR}/
cp -r resources ${BUILD_DIR}/
cp -r routes ${BUILD_DIR}/
cp -r storage ${BUILD_DIR}/
cp -r vendor ${BUILD_DIR}/

# Configuration files
cp -r .ebextensions ${BUILD_DIR}/
cp -r .platform ${BUILD_DIR}/
cp -r .elasticbeanstalk ${BUILD_DIR}/

# Root files
cp artisan ${BUILD_DIR}/
cp composer.json ${BUILD_DIR}/
cp composer.lock ${BUILD_DIR}/

# Copy .ebignore if exists
if [ -f .ebignore ]; then
    cp .ebignore ${BUILD_DIR}/
fi

echo -e "${GREEN}✓ Application files copied${NC}"
echo ""

# Step 6: Clean up unnecessary files from build
echo -e "${YELLOW}Step 6/8: Removing unnecessary files...${NC}"

# Remove storage contents (will be regenerated)
rm -rf ${BUILD_DIR}/storage/logs/*
rm -rf ${BUILD_DIR}/storage/framework/cache/*
rm -rf ${BUILD_DIR}/storage/framework/sessions/*
rm -rf ${BUILD_DIR}/storage/framework/views/*
rm -rf ${BUILD_DIR}/storage/app/public/*

# Create necessary .gitkeep files
touch ${BUILD_DIR}/storage/logs/.gitkeep
touch ${BUILD_DIR}/storage/framework/cache/.gitkeep
touch ${BUILD_DIR}/storage/framework/sessions/.gitkeep
touch ${BUILD_DIR}/storage/framework/views/.gitkeep
touch ${BUILD_DIR}/storage/app/public/.gitkeep

# Remove test files
rm -rf ${BUILD_DIR}/database/factories
rm -rf ${BUILD_DIR}/database/seeders/DemoUserSeeder.php
rm -rf ${BUILD_DIR}/database/seeders/SampleProphecySeeder.php

# Remove markdown documentation (keep only essential)
rm -f ${BUILD_DIR}/*.md
cp README.md ${BUILD_DIR}/ 2>/dev/null || true

echo -e "${GREEN}✓ Unnecessary files removed${NC}"
echo ""

# Step 7: Create deployment package
echo -e "${YELLOW}Step 7/8: Creating ZIP package...${NC}"
cd ${BUILD_DIR}
zip -r ../${PACKAGE_NAME} . -q
cd ..

echo -e "${GREEN}✓ ZIP package created${NC}"
echo ""

# Step 8: Clean up temporary directory
echo -e "${YELLOW}Step 8/8: Cleaning up...${NC}"
rm -rf ${BUILD_DIR}
echo -e "${GREEN}✓ Temporary files removed${NC}"
echo ""

# Get package size
PACKAGE_SIZE=$(du -h ${PACKAGE_NAME} | cut -f1)

# Summary
echo -e "${BLUE}================================================${NC}"
echo -e "${GREEN}✓ Deployment package created successfully!${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""
echo -e "${YELLOW}Package Details:${NC}"
echo -e "  Name: ${GREEN}${PACKAGE_NAME}${NC}"
echo -e "  Size: ${GREEN}${PACKAGE_SIZE}${NC}"
echo -e "  Location: ${GREEN}$(pwd)/${PACKAGE_NAME}${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo -e "  1. Upload to EB: ${BLUE}eb deploy --staged${NC}"
echo -e "  2. Or manual upload via AWS Console"
echo -e "  3. Or use: ${BLUE}aws elasticbeanstalk create-application-version${NC}"
echo ""
echo -e "${YELLOW}Quick Deploy:${NC}"
echo -e "  ${BLUE}eb deploy jv-prophecy-prod${NC}"
echo ""
echo -e "${GREEN}Done!${NC}"

