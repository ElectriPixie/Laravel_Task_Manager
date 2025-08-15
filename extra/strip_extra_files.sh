#!/usr/bin/env bash

# Check if path argument is provided
if [ -z "$1" ]; then
    echo "Usage: $0 /path/to/laravel/project"
    exit 1
fi

PROJECT_DIR="$1"

# Make sure the directory exists
if [ ! -d "$PROJECT_DIR" ]; then
    echo "Directory $PROJECT_DIR does not exist!"
    exit 1
fi

echo "Cleaning Laravel project at $PROJECT_DIR ..."

# Remove queue and batch migrations
rm -f "$PROJECT_DIR"/database/migrations/*_create_jobs_table.php
rm -f "$PROJECT_DIR"/database/migrations/*_create_failed_jobs_table.php

# Remove the default welcome page
rm -f "$PROJECT_DIR"/resources/views/welcome.blade.php

# Remove default CSS/JS (optional)
rm -f "$PROJECT_DIR"/resources/css/app.css
rm -f "$PROJECT_DIR"/resources/js/app.js

# Remove tests folder (optional)
rm -rf "$PROJECT_DIR"/tests

# Clear cached files
rm -rf "$PROJECT_DIR"/bootstrap/cache/*.php

echo "Cleanup complete."