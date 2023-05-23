import os
import zipfile
import hashlib

# Get the directory path of the script
script_dir = os.path.dirname(os.path.abspath(__file__))

# Define the source directory containing the files to be zipped
source_dir = os.path.join(script_dir, '..', 'WP')

# Define the destination directory for the zip file
dest_dir = os.path.join(script_dir, '..')

# Define the name of the zip file
zip_name = 'safeopt-tags.zip'

# Define the files to be included in the zip
files_to_zip = ['safeopt-tags.php', 'uninstall.php']

# Change to the source directory
os.chdir(source_dir)

# Create the zip file
zip_path = os.path.join(dest_dir, zip_name)
with zipfile.ZipFile(zip_path, 'w') as zip_file:
    for file in files_to_zip:
        zip_file.write(file)

# Verify if the zip file was created successfully
if os.path.exists(zip_path):
    print(f"Zip file created successfully: {zip_path}")
else:
    print("Failed to create the zip file.")

# Read the entire file
with open(zip_path, 'rb') as file:
    file_contents = file.read()

# Calculate the MD5 hash
md5_hash = hashlib.md5(file_contents).hexdigest()

# Print the MD5 hash
print("Update the zip file hash in the readme.md file")
print("MD5 hash of {}: {}".format(zip_name, md5_hash))
