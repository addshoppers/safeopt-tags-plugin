# SafeOpt Tags Plugin
## WordPress Plugin for WooCommerse

### Information
- plugin name: safeopt-tags
- works with: WordPress sites with WooCommerce
- install method: upload zip file
- version: 1.0.0
- developer: [Addshoppers](https://www.addshoppers.com/)
- contact: [help@addshoppers.com](mailto:help@addshoppers.com)
- MD5 hash of safeopt-tags.zip: f89bc1bbdfd041a1c59007891244750a

---

### Description
This is a WordPress plugin that installs the SafeOpt Global Site Tag and Conversion Tracking Tag on your site.  

**NOTE: This works with WooCommerce Only, please contact [help@addshoppers.com](mailto:help@addshoppers.com) if you need support for other e-commerce platform.**

---

### Install Steps

Please visit [SafetOpt Knowledge Base]([https://addshoppers.atlassian.net/servicedesk/customer/portal/1/article/2747334658](https://addshoppers.atlassian.net/wiki/spaces/CS/pages/2727378961/SafeOpt+Help+Articles+and+Topics)) for the install steps.

### GitHub Repo Notes

Working files will be saved under the WP directory.  When a new version of the plugin is ready to be released a new zip file must be created using scripts `make_zip_file.sh` or `make_zip_file.py`in the util directory.  

When a new version is release please do the following:
1. update the version in `safeopt-tags.php`
2. Replace the current zip file with the new version zip file using provided scripts 
3. update readme.md with the latests version and any other updates required
4. update `changelog.md` in util directory
