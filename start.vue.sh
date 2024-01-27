#!/bin/bash

# Change to the /app directory
cd /app

# Print a message to the console
echo "Installing npm dependencies. This may take a few minutes..."

# Install npm dependencies
npm install --silent --no-progress

npm install -g serve --silent --no-progress

# Run npm start
echo "Starting the application..."
npm run build:production

# Run serve
serve -s dist -l 3001