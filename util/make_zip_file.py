import os
import zipfile

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

# Optional: Verify if the zip file was created successfully
if os.path.exists(zip_path):
    print(f"Zip file created successfully: {zip_path}")
else:
    print("Failed to create the zip file.")
