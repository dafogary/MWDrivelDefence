# DrivelDefence MediaWiki Extension - Installation Guide

## Prerequisites

- MediaWiki 1.35.0 or later
- PHP 7.4 or later
- Modern web browser with JavaScript enabled

## Installation Steps

### 1. Download the Extension

Clone or download the extension to your MediaWiki installation:

```bash
cd /path/to/your/mediawiki/extensions/
git clone https://github.com/yourusername/DrivelDefence.git
```

### 2. Enable the Extension

Add the following line to your `LocalSettings.php` file:

# DrivelDefence MediaWiki Extension - Installation Guide

## Prerequisites

- MediaWiki 1.35.0 or later
- PHP 7.4 or later
- Modern web browser with JavaScript enabled

## Installation Steps

### 1. Download the Extension

Clone or download the extension to your MediaWiki installation:

```bash
cd /path/to/your/mediawiki/extensions/
git clone https://github.com/yourusername/DrivelDefence.git
```

### 2. Enable the Extension

Add the following line to your `LocalSettings.php` file:

```php
wfLoadExtension( 'MWDrivelDefence' );
```

### 3. Update Database (if required)

Run the MediaWiki update script:

```bash
php maintenance/update.php
```

### 4. Verify Installation

1. Go to `Special:Version` in your wiki
2. Look for "DrivelDefence" in the list of installed extensions
3. Visit `Special:DrivelDefence` to test the functionality

## Directory Structure

After installation, your extension directory should look like this:

```
extensions/DrivelDefence/
├── extension.json                 # Extension registration
├── DrivelDefence.alias.php       # Special page aliases
├── README.md                      # Documentation
├── CHANGELOG.md                   # Version history
├── package.json                   # npm configuration
├── .gitignore                     # Git ignore rules
├── includes/                      # PHP classes
│   ├── SpecialDrivelDefence.php  # Special page class
│   └── DrivelDefenceHooks.php    # Hook handlers
├── i18n/                         # Internationalization
│   └── en.json                   # English messages
└── resources/                    # Frontend assets
    ├── css/
    │   └── DrivelDefence.css     # Styles
    ├── js/
    │   ├── DrivelDefence.js      # Main JavaScript
    │   └── DrivelDefenceshared.js # Word database & analysis
    └── images/
        └── plainenglishbuster.png # Logo
```

## Configuration Options

### Permissions

By default, all users can access the special page. To restrict access, add to `LocalSettings.php`:

```php
# Restrict to registered users
$wgSpecialPageGroups['DrivelDefence'] = 'users';

# Or create a custom right
$wgAvailableRights[] = 'driveldefence-use';
$wgGroupPermissions['user']['driveldefence-use'] = true;
```

### Automatic Talk Page Analysis

To enable automatic analysis when pages are saved:

```php
# Enable automatic analysis
$wgDrivelDefenceAutoAnalyze = true;

# Configure which namespaces to analyze
$wgDrivelDefenceNamespaces = [ 0, 4 ]; // 0=Main, 4=Project

# Set minimum word count threshold
$wgDrivelDefenceMinWords = 50;

# Set maximum recommended sentence length
$wgDrivelDefenceMaxSentenceLength = 25;
```

**Note**: Talk page analysis is disabled by default to avoid unwanted automated edits. Enable it only if you want automatic plain English feedback on your wiki.

### Custom Styling

To customize the appearance, add CSS to your `MediaWiki:Common.css` page:

```css
.driveldefence-container {
    /* Your custom styles */
}
```

## Usage Examples

### Special Page
Visit `Special:DrivelDefence` and paste text to analyze.

### Parser Function
Use in wiki pages:
```wiki
{{#drivelcheck:Your text to check goes here.}}
```

### Template Integration
Create a template for common usage:
```wiki
<includeonly>{{#drivelcheck:{{{1}}}}}</includeonly>
```

Then use: `{{PlainEnglishCheck|Text to check}}`

### Auto Analyse
MWDrivelDefence is designed to automatically analyze a page when it's saved and post suggestions to the corresponding talk page, but only if you enable it. 

Conditions for Posting: If auto-analysis is enabled, it will post to the talk page only when:

The page is in a namespace specified in $wgMWDrivelDefenceNamespaces (by default, the Main and Project namespaces).
The page is not a talk page itself.
The page has enough words to meet the $wgMWDrivelDefenceMinWords setting.
The analysis finds issues (long sentences or words with simpler alternatives).
To enable this feature, you must add the following line to your LocalSettings.php:

```php
<?php
$wgMWDrivelDefenceAutoAnalyze = true;
```

Without this setting, the automatic analysis and talk page posting will not happen.



## Troubleshooting

### Extension Not Appearing
- Check that `wfLoadExtension( 'MWDrivelDefence' );` is in `LocalSettings.php`
- Verify file permissions are correct
- Clear MediaWiki cache

### JavaScript Errors
- Check browser console for errors
- Ensure ResourceLoader is working
- Verify all files are present

### Special Page Not Found
- Run `php maintenance/update.php`
- Check that aliases are working
- Clear cache and try again

## Support

For issues and support:
1. Check the GitHub issues page
2. Verify your MediaWiki version compatibility
3. Check PHP error logs
4. Test with default MediaWiki theme

## Uninstallation

To remove the extension:

1. Remove `wfLoadExtension( 'MWDrivelDefence' );` from `LocalSettings.php`
2. Delete the extension directory
3. Clear MediaWiki cache

The extension doesn't create database tables, so no cleanup is needed.
```php
wfLoadExtension( 'MWDrivelDefence' );
```

### 3. Update Database (if required)

Run the MediaWiki update script:

```bash
php maintenance/update.php
```

### 4. Verify Installation

1. Go to `Special:Version` in your wiki
2. Look for "DrivelDefence" in the list of installed extensions
3. Visit `Special:DrivelDefence` to test the functionality

## Directory Structure

After installation, your extension directory should look like this:

```
extensions/DrivelDefence/
├── extension.json                 # Extension registration
├── DrivelDefence.alias.php       # Special page aliases
├── README.md                      # Documentation
├── CHANGELOG.md                   # Version history
├── package.json                   # npm configuration
├── .gitignore                     # Git ignore rules
├── includes/                      # PHP classes
│   ├── SpecialDrivelDefence.php  # Special page class
│   └── DrivelDefenceHooks.php    # Hook handlers
├── i18n/                         # Internationalization
│   └── en.json                   # English messages
└── resources/                    # Frontend assets
    ├── css/
    │   └── DrivelDefence.css     # Styles
    ├── js/
    │   ├── DrivelDefence.js      # Main JavaScript
    │   └── DrivelDefenceshared.js # Word database & analysis
    └── images/
        └── plainenglishbuster.png # Logo
```

## Configuration Options

### Permissions

By default, all users can access the special page. To restrict access, add to `LocalSettings.php`:

```php
# Restrict to registered users
$wgSpecialPageGroups['DrivelDefence'] = 'users';

# Or create a custom right
$wgAvailableRights[] = 'driveldefence-use';
$wgGroupPermissions['user']['driveldefence-use'] = true;
```

### Automatic Talk Page Analysis

To enable automatic analysis when pages are saved:

```php
# Enable automatic analysis
$wgDrivelDefenceAutoAnalyze = true;

# Configure which namespaces to analyze
$wgDrivelDefenceNamespaces = [ 0, 4 ]; // 0=Main, 4=Project

# Set minimum word count threshold
$wgDrivelDefenceMinWords = 50;

# Set maximum recommended sentence length
$wgDrivelDefenceMaxSentenceLength = 25;
```

**Note**: Talk page analysis is disabled by default to avoid unwanted automated edits. Enable it only if you want automatic plain English feedback on your wiki.

### Custom Styling

To customize the appearance, add CSS to your `MediaWiki:Common.css` page:

```css
.driveldefence-container {
    /* Your custom styles */
}
```

## Usage Examples

### Special Page
Visit `Special:DrivelDefence` and paste text to analyze.

### Parser Function
Use in wiki pages:
```wiki
{{#drivelcheck:Your text to check goes here.}}
```

### Template Integration
Create a template for common usage:
```wiki
<includeonly>{{#drivelcheck:{{{1}}}}}</includeonly>
```

Then use: `{{PlainEnglishCheck|Text to check}}`

### Auto Analyse
MWDrivelDefence is designed to automatically analyze a page when it's saved and post suggestions to the corresponding talk page, but only if you enable it. 

Conditions for Posting: If auto-analysis is enabled, it will post to the talk page only when:

 1. The page is in a namespace specified in $wgMWDrivelDefenceNamespaces (by default, the Main and Project namespaces).
 2. The page is not a talk page itself.
 3. The page has enough words to meet the $wgMWDrivelDefenceMinWords setting.
 4. The analysis finds issues (long sentences or words with simpler alternatives).

To enable this feature, you must add the following line to your LocalSettings.php:

```php
<?php
$wgMWDrivelDefenceAutoAnalyze = true;
```

Without this setting, the automatic analysis and talk page posting will not happen.

If you want to use the auto analysing tool in namespaces that are not the main or project namespace, include and modify the following to your LocalSettings.php:

```php
<?php
$wgMWDrivelDefenceNamespaces = [
    NS_MAIN,
    NS_PROJECT,
    NS_FOO,
    NS_BAR
];
```

## Troubleshooting

### Extension Not Appearing
- Check that `wfLoadExtension( 'MWDrivelDefence' );` is in `LocalSettings.php`
- Verify file permissions are correct
- Clear MediaWiki cache

### JavaScript Errors
- Check browser console for errors
- Ensure ResourceLoader is working
- Verify all files are present

### Special Page Not Found
- Run `php maintenance/update.php`
- Check that aliases are working
- Clear cache and try again

## Support

For issues and support:
1. Check the GitHub issues page
2. Verify your MediaWiki version compatibility
3. Check PHP error logs
4. Test with default MediaWiki theme

## Uninstallation

To remove the extension:

1. Remove `wfLoadExtension( 'MWDrivelDefence' );` from `LocalSettings.php`
2. Delete the extension directory
3. Clear MediaWiki cache

The extension doesn't create database tables, so no cleanup is needed.
