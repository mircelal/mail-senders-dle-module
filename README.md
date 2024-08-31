# Mail-Senders DLE Module

## Overview
The Mail-Senders DLE Module is a custom module designed for DataLife Engine (DLE) CMS. It provides an interface for managing and listing users who have sent emails through the website. The module allows administrators to view the details of email senders, including their name, surname, phone number, and email address.

## Features
- **User Data Storage:** Saves the sender's first name, last name, phone number, and email address in the database.
- **Admin Interface:** Displays a table of senders with pagination, enabling admins to browse through records.
- **Data Management:** Provides options to copy selected emails and phone numbers to the clipboard.
- **Easy Installation:** Simple installation and integration with DLE's admin panel.

## Installation

1. **Upload Plugin:**
   - Upload the plugin, and it will automatically place the following files in the required directories:
     - `mailsenders.php` file will be placed in the `engine/modules/mailsenders/` directory.
     - `mailsenders.php` admin file will be placed in the `engine/inc/` directory.

2. **Database Setup:**
   - The module will automatically create the required database table upon installation.
   - The table will store the email sender information.

3. **Enable the Module:**
   - The module will automatically add an entry in the DLE admin panel upon activation.

4. **Usage:**
   - The function `mail_sender_info_save($name, $last_name, $tel, $email);` is globally available for saving sender data.
   - Access the module through the admin panel to view and manage the email sender data.

## Requirements
- DataLife Engine (DLE) Version 16.0 or higher.

## Uninstallation
To remove the module, simply deactivate it from the DLE admin panel. This will remove the database table and the entry from the admin panel.

**Warning:** Uninstalling the module will **permanently delete** all stored data and remove the database table along with its admin panel entry. This action cannot be undone.

## License
This module is proprietary and is intended for internal use within the company. Redistribution or use outside the company is prohibited.

## Author
**Ehmedli Ehmed** - Okmedia MMC
