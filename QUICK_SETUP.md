# MW Drivel Defence - Quick Setup for Automatic Analysis

## Issue: Automatic Analysis Not Running

The automatic analysis feature is **disabled by default** for safety reasons. Here's how to enable it:

## Step 1: Enable Automatic Analysis

Add these lines to your MediaWiki `LocalSettings.php` file:

```php
# Enable automatic analysis when pages are saved
$wgMWDrivelDefenceAutoAnalyze = true;

# Namespaces to analyze (0=Main, 4=Project, etc.)
$wgMWDrivelDefenceNamespaces = [ 0, 4 ];

# Minimum word count to trigger analysis
$wgMWDrivelDefenceMinWords = 50;

# Maximum recommended sentence length
$wgMWDrivelDefenceMaxSentenceLength = 25;
```

## Step 2: Clear Cache

After adding the configuration, clear your MediaWiki cache:

```bash
# Method 1: Run maintenance script
php maintenance/update.php

# Method 2: Or clear cache manually
rm -rf cache/*
```

## Step 3: Test the Feature

1. **Edit a page** in the Main namespace (articles) with at least 50 words
2. **Save the page** 
3. **Check the talk page** - it should automatically create a new section with analysis results

## Troubleshooting

### Check Your Configuration
Verify the settings are working by adding this debug line to `LocalSettings.php`:
```php
error_log("MW Drivel Defence Auto-Analyze: " . ($wgMWDrivelDefenceAutoAnalyze ? "ENABLED" : "DISABLED"));
```

### Common Issues

**1. Wrong Namespace**
- The default setting only analyzes namespace 0 (Main articles) and 4 (Project pages)
- User pages are namespace 2, talk pages are namespace 1
- Check which namespace your test page is in

**2. Too Few Words**
- Default minimum is 50 words
- Short test pages won't trigger analysis
- Lower the threshold: `$wgMWDrivelDefenceMinWords = 10;`

**3. No Issues Found**
- Analysis only posts to talk page if it finds problems
- Try adding complex sentences (25+ words) or difficult words
- Look for words like "utilize", "facilitate", "demonstrate" to trigger alternatives

**4. Permissions**
- The saving user must have permissions to create/edit talk pages
- Check user rights and namespace permissions

### Debug Logging

Enable debug logging to see what's happening:

```php
# Add to LocalSettings.php
$wgDebugLogGroups['MWDrivelDefence'] = '/path/to/debug.log';
$wgShowDebug = true;
```

### Test Content Example

Use this text to test (it has issues that should trigger analysis):

```
This is a demonstration of text that should facilitate the activation of the automatic analysis functionality. 
This sentence is deliberately constructed to be excessively long and should definitely trigger the analysis because it contains more than twenty-five words and uses complex vocabulary that could be simplified. 
Additionally, we can utilize sophisticated terminology to demonstrate the functionality and ensure that alternative suggestions will be provided.
```

## Expected Talk Page Output

When working correctly, you should see a new section on the talk page like:

```
== Plain English & Accessibility Analysis - [timestamp] ==

This page was automatically analyzed for plain English compliance and WCAG 2.2 AA accessibility...

'''Plain English Summary:'''
* Total words: 67
* Total sentences: 3
* Average words per sentence: 22.3
* Sentences over 25 words: 1
* Words with simpler alternatives: 4

'''Accessibility Analysis:'''
* Overall Accessibility Score: 68/100
* Reading Level: Flesch-Kincaid 12.3 (target: 8 or below)
```

## Quick Test Commands

After configuration, test with these MediaWiki API calls:

```bash
# Check if extension is loaded
curl "http://yourwiki.com/api.php?action=query&meta=siteinfo&siprop=extensions&format=json" | grep -i drivel

# Check configuration
php maintenance/eval.php --conf /path/to/LocalSettings.php
> var_dump($wgMWDrivelDefenceAutoAnalyze);
```

## Success Indicators

✅ Configuration added to LocalSettings.php  
✅ Cache cleared  
✅ Test page saved with 50+ words  
✅ Talk page created automatically  
✅ Analysis section appears on talk page  

If you're still having issues after following these steps, check the MediaWiki error logs and let me know what you find!