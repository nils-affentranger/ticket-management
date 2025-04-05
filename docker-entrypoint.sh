#!/bin/sh
set -e

# Create Laravel storage directories if they don't exist
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/bootstrap/cache

# Set proper permissions
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

# Make sure the directories are owned by www-data
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Function to wait for MySQL to be ready
wait_for_db() {
  echo "Waiting for database to be ready..."
  
  # Maximum number of attempts
  max_attempts=30
  
  # Counter for attempts
  attempt_num=1
  
  # Wait time between attempts (in seconds)
  wait_time=2
  
  # Get database connection details from Laravel .env file if it exists
  if [ -f /var/www/.env ]; then
    DB_HOST=$(grep DB_HOST /var/www/.env | cut -d '=' -f2)
    DB_PORT=$(grep DB_PORT /var/www/.env | cut -d '=' -f2)
  fi
  
  # Use default values if not found in .env
  DB_HOST=${DB_HOST:-db}
  DB_PORT=${DB_PORT:-3306}
}

# Check if we should run migrations
if [ "$RUN_MIGRATIONS" = "true" ]; then
  # Wait for database connection
  wait_for_db
  
  if [ $? -eq 0 ]; then
    echo "Running migrations..."
    cd /var/www
    php artisan migrate --force
    
    # Optional: run seeds if enabled
    if [ "$RUN_SEEDS" = "true" ]; then
      echo "Running seeds..."
      php artisan db:seed --force
    fi
  else
    echo "Skipping migrations due to database connection failure."
  fi
fi

# Execute the passed command
exec "$@"