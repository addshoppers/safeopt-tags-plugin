#!/bin/bash

# Get the directory path of the script
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Define the source directory containing the files to be zipped
SOURCE_DIR="${SCRIPT_DIR}/../WP"

# Define the destination directory for the zip file
DEST_DIR="${SCRIPT_DIR}/../"

# Define the name of the zip file
ZIP_NAME="safeopt-tags.zip"

# Change to the source directory
cd "$SOURCE_DIR" || exit 1

# Create the zip file
zip -r "$DEST_DIR/$ZIP_NAME" safeopt-tags.php uninstall.php

# Optional: Verify if the zip file was created successfully
if [ -f "$DEST_DIR/$ZIP_NAME" ]; then
  echo "Zip file created successfully: $DEST_DIR/$ZIP_NAME"
else
  echo "Failed to create the zip file."
fi
