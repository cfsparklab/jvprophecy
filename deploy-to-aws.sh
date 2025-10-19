#!/bin/bash
# AWS Elastic Beanstalk Deployment Script
# JV Prophecy Manager

set -e  # Exit on error

echo "================================================"
echo "AWS Elastic Beanstalk Deployment Script"
echo "JV Prophecy Manager"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored messages
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if EB CLI is installed
if ! command -v eb &> /dev/null; then
    print_error "EB CLI is not installed. Please install it first:"
    echo "  pip install awsebcli --upgrade"
    exit 1
fi

print_success "EB CLI is installed"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed"
    exit 1
fi

print_success "Composer is installed"

# Ask for environment
echo ""
print_info "Select deployment environment:"
echo "  1) Production (jv-prophecy-prod)"
echo "  2) Staging (jv-prophecy-staging)"
read -p "Enter choice [1-2]: " env_choice

case $env_choice in
    1)
        ENVIRONMENT="jv-prophecy-prod"
        ;;
    2)
        ENVIRONMENT="jv-prophecy-staging"
        ;;
    *)
        print_error "Invalid choice"
        exit 1
        ;;
esac

print_info "Deploying to: $ENVIRONMENT"
echo ""

# Confirmation
read -p "Are you sure you want to deploy to $ENVIRONMENT? (yes/no): " confirm
if [ "$confirm" != "yes" ]; then
    print_error "Deployment cancelled"
    exit 0
fi

echo ""
print_info "Starting deployment process..."
echo ""

# Step 1: Install dependencies
print_info "Step 1/7: Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
print_success "Dependencies installed"

# Step 2: Clear caches
print_info "Step 2/7: Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared"

# Step 3: Run tests (optional)
read -p "Run tests before deployment? (yes/no): " run_tests
if [ "$run_tests" = "yes" ]; then
    print_info "Step 3/7: Running tests..."
    php artisan test
    print_success "Tests passed"
else
    print_info "Step 3/7: Skipping tests"
fi

# Step 4: Create deployment archive
print_info "Step 4/7: Creating deployment archive..."
eb deploy $ENVIRONMENT --staged
print_success "Archive created"

# Step 5: Deploy to AWS
print_info "Step 5/7: Deploying to AWS Elastic Beanstalk..."
print_info "This may take several minutes..."
eb deploy $ENVIRONMENT

# Step 6: Check deployment status
print_info "Step 6/7: Checking deployment status..."
eb status $ENVIRONMENT

# Step 7: Run post-deployment tasks
print_info "Step 7/7: Running post-deployment tasks..."
print_info "Connecting to instance..."
eb ssh $ENVIRONMENT -c "cd /var/app/current && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache"
print_success "Post-deployment tasks completed"

echo ""
print_success "================================================"
print_success "Deployment completed successfully!"
print_success "================================================"
echo ""

# Display environment info
print_info "Environment URL:"
eb status $ENVIRONMENT | grep "CNAME"

echo ""
print_info "To view logs, run: eb logs $ENVIRONMENT"
print_info "To SSH into instance, run: eb ssh $ENVIRONMENT"
print_info "To open app in browser, run: eb open $ENVIRONMENT"
echo ""

