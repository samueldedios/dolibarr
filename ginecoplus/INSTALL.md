# GinecoPlus Module Installation Guide

## Prerequisites

- Dolibarr instance version 15.0 or higher
- Administrator access to Dolibarr
- MySQL/MariaDB database

## Installation Steps

1. **Copy the module**
   - Copy the `ginecoplus` folder to `[DOLIBARR_PATH]/htdocs/modules/`

2. **Enable the module**
   - Log in to Dolibarr as an administrator
   - Navigate to Setup > Modules/Applications
   - Search for "GinecoPlus"
   - Click on the module name to enable it

3. **Configure permissions**
   - Go to Setup > Users > User/Group permissions
   - Assign GinecoPlus permissions to appropriate users/groups

4. **Database setup**
   - The module will automatically create necessary tables on first activation

## Configuration

After installation, configure the module settings:

1. Navigate to GinecoPlus > Setup
2. Configure your preferences
3. Save changes

## Troubleshooting

If the module doesn't appear:
- Ensure the folder is in the correct location
- Check file permissions (must be readable by web server)
- Clear Dolibarr cache if necessary
- Check the logs in the `logs` folder

## Support

For issues or questions, visit: https://github.com/samueldedios/dolibarr