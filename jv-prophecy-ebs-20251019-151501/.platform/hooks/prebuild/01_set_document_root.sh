#!/bin/bash
# Set Laravel public directory as document root

set -e

echo "Configuring Nginx document root for Laravel..."

# The document root should point to /public for Laravel
# This is handled by the Nginx configuration in .platform/nginx/conf.d/

echo "Document root configuration completed."

