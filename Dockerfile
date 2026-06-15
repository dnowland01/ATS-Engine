FROM php:8.2-cli

# Install extensions if needed
RUN docker-php-ext-install pdo pdo_mysql

# Create app directory
WORKDIR /app

# Copy project files
COPY . /app

# Expose port for built-in PHP server
EXPOSE 8000

# Default command: run the web dashboard
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
