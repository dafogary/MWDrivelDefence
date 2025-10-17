# MW Drivel Defence - MediaWiki Extension

MW Drivel Defence is a comprehensive MediaWiki extension that provides plain English checking functionality and WCAG 2.2 AA accessibility analysis. Based on guidance from the Plain English Campaign, it helps users create more accessible and readable content.

## Features

### Plain English Analysis
- **Special Page**: Provides a dedicated page (`Special:MWDrivelDefence`) for analyzing text
- **Parser Function**: Allows inline text checking using `{{#drivelcheck:text to check}}`
- **Automatic Analysis**: Optional automatic analysis of pages when saved, with results posted to talk pages
- **Word Analysis**: Identifies words that could be replaced with simpler alternatives
- **Sentence Analysis**: Highlights long sentences that may be difficult to read
- **Statistical Summary**: Provides word counts, sentence counts, and readability metrics

### WCAG 2.2 AA Accessibility Analysis
- **📖 Reading Level Analysis**: 
  - Flesch-Kincaid Grade Level calculation
  - Flesch Reading Ease scoring
  - Gunning Fog Index assessment
- **🧠 Dyslexia-Friendly Analysis**:
  - Paragraph length evaluation
  - Sentence complexity assessment
  - Complex word identification
  - Dyslexia-friendly design recommendations
- **🔊 Screen Reader Compatibility**:
  - Alt text verification for images
  - Heading structure analysis
  - Form label checking
  - Table header validation
- **🎨 Color Contrast Checking**:
  - WCAG AA/AAA compliance verification
  - Interactive contrast ratio calculator
  - Color accessibility recommendations
- **📊 Overall WCAG Compliance Score**: Comprehensive accessibility rating

## Installation

1. Download or clone this extension to your MediaWiki `extensions/` directory:
   ```bash
   cd extensions/
   git clone https://github.com/dafogary/MWDrivelDefence.git
   ```

2. Add the following line to your `LocalSettings.php` file:
   ```php
   wfLoadExtension( 'MWDrivelDefence' );
   ```

3. Navigate to Special:Version on your wiki to verify that the extension is successfully installed.

## Usage

### Special Page

Visit `Special:MWDrivelDefence` on your wiki to access the comprehensive text and accessibility analysis tool:

1. Enter or paste text into the text area
2. Choose your analysis options:
   - **Plain English Options**:
     - Report all sentences or only long sentences (20+ words)
     - Enable/disable alternative word suggestions
   - **WCAG 2.2 AA Accessibility Options**:
     - Comprehensive WCAG compliance analysis
     - Reading level analysis (Flesch-Kincaid, Gunning Fog)
     - Dyslexia-friendly design principles
     - Screen reader compatibility
3. Click "Analyze Text" to generate a comprehensive report

### Parser Function

Use the parser function to check text inline within wiki pages:

```
{{#drivelcheck:This is some text that you want to check for plain English usage and accessibility compliance.}}
```

This will display the text with a "Check Plain English" button that users can click to analyze the text.

## Accessibility Analysis Features

### Reading Level Analysis
The extension calculates multiple readability metrics:
- **Flesch-Kincaid Grade Level**: Estimates the U.S. school grade level needed to understand the text
- **Flesch Reading Ease**: Scores text on a 0-100 scale (higher scores = easier to read)
- **Gunning Fog Index**: Estimates years of formal education needed to understand the text

**Target Scores for Accessibility**:
- Flesch-Kincaid Grade Level: 8 or below
- Flesch Reading Ease: 60 or above  
- Gunning Fog Index: 12 or below

### Dyslexia-Friendly Analysis
Checks content against dyslexia-friendly design principles:
- **Paragraph Length**: Identifies paragraphs over 40 words
- **Sentence Complexity**: Flags sentences over 20 words or with high syllable density
- **Complex Words**: Lists words with 4+ syllables
- **Recommendations**: Provides specific suggestions for improvement

### Screen Reader Compatibility
Analyzes HTML structure for screen reader accessibility:
- **Images**: Checks for missing alt text
- **Headings**: Validates proper heading hierarchy (h1, h2, h3, etc.)
- **Forms**: Ensures form inputs have labels or aria-labels
- **Tables**: Verifies data tables have header cells

### Color Contrast Analysis
Built-in tool for testing color combinations:
- **WCAG AA Compliance**: 4.5:1 ratio for normal text
- **WCAG AAA Compliance**: 7:1 ratio for enhanced accessibility
- **Large Text**: 3:1 ratio for 18pt+ or 14pt+ bold text
- **Interactive Calculator**: Test any color combination instantly

## Testing the Accessibility Features

A test page is included (`accessibility-test.html`) that demonstrates all the new accessibility analysis features. Open this file in a web browser to:
- Test reading level analysis with sample text
- Use the color contrast checker
- See how the WCAG compliance scoring works
- Understand the dyslexia-friendly recommendations

### API Integration

The extension integrates with MediaWiki's resource loader system and provides:
- Responsive design that works on mobile devices
- WCAG 2.2 AA compliant interface design
- Integration with MediaWiki's notification system
- Proper internationalization support

## Configuration

The extension works out of the box with no additional configuration required. However, you can customize:

### Basic Settings
- **Permissions**: Control who can access the special page by modifying user rights
- **Interface Messages**: Customize messages by editing the i18n files
- **Styling**: Override CSS styles to match your wiki's theme

### Automatic Analysis Settings
Add these to your `LocalSettings.php` to enable automatic talk page analysis:

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

## Advanced Usage

### Template Integration
Create a template for common usage:
```wiki
<includeonly>{{#drivelcheck:{{{1}}}}}</includeonly>
```

Then use: `{{PlainEnglishCheck|Text to check}}`

### Automatic Talk Page Analysis
When enabled, the extension automatically analyzes pages when they are saved and posts results to the talk page if issues are found:

- **Triggered by**: Page saves in configured namespaces
- **Minimum threshold**: Only analyzes pages with sufficient content (configurable)
- **Talk page topics**: Creates new sections with analysis results
- **Summary includes**: Word counts, sentence analysis, and specific recommendations
- **Non-intrusive**: Only posts when actual issues are detected

Example talk page entry:
```
== Plain English Analysis ==

This page was automatically analyzed for plain English compliance on July 1, 2025.

'''Summary:'''
* Total words: 342
* Total sentences: 18
* Average words per sentence: 19.0
* Sentences over 25 words: 3
* Words with simpler alternatives: 7

'''Recommendations:'''
⚠ Found 3 sentences with more than 25 words. Consider breaking these into shorter sentences.
⚠ Found 7 words that could be replaced with simpler alternatives.
```

### Batch Analysis Script
For more control over when analysis occurs, use the included batch analysis script. This is particularly useful for:
- Analyzing multiple pages at once
- Running analysis on a schedule via cron jobs
- Processing recently edited content
- Avoiding real-time performance impact on page saves

#### Basic Usage
```bash
# Navigate to your MediaWiki directory
cd /var/www/html/your-wiki

# Analyze a specific page
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --page="Page_name"

# Test what would be analyzed without making changes
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --page="Page_name" --dry-run
```

#### Analyze Recent Content
```bash
# Analyze pages modified in the last 24 hours
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=1

# Analyze pages modified in the last week (7 days)
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=7

# Analyze pages modified in the last month (30 days)
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=30
```

#### Limit Analysis Scope
```bash
# Limit to 20 pages maximum
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=7 --limit=20

# Analyze only main namespace (0) pages
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=1 --namespace=0

# Combine options: recent pages in main namespace with limit
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=1 --namespace=0 --limit=10
```

#### Advanced Options
```bash
# Force re-analysis of pages already analyzed
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=7 --force

# Preview what would be analyzed (dry run)
php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=7 --limit=50 --dry-run
```

#### Automated Analysis with Cron Jobs
Set up automated analysis to run regularly without manual intervention:

**Daily Analysis (recommended)**
```bash
# Edit your crontab
crontab -e

# Add this line to analyze recent pages daily at 2 AM
0 2 * * * cd /var/www/html/your-wiki && php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=1 --limit=20 >/dev/null 2>&1
```

**Weekly Analysis**
```bash
# Run every Sunday at 3 AM to catch any missed pages
0 3 * * 0 cd /var/www/html/your-wiki && php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=7 --limit=50 >/dev/null 2>&1
```

**Monthly Comprehensive Analysis**
```bash
# Run on the 1st of each month at 4 AM
0 4 1 * * cd /var/www/html/your-wiki && php extensions/MWDrivelDefence/analyzePagesForPlainEnglish.php --recent=30 --limit=100 --force >/dev/null 2>&1
```

#### Script Features
- **Smart Duplicate Prevention**: Avoids re-analyzing pages that were analyzed in the last 30 days (unless `--force` is used)
- **Issue-Based Filtering**: Only posts to talk pages when actual issues are found (long sentences, complex words)
- **Namespace Respect**: Only analyzes namespaces configured in `$wgMWDrivelDefenceNamespaces`
- **Performance Optimization**: Includes delays between pages to prevent server overload
- **Detailed Reporting**: Provides comprehensive summaries of analysis results

#### Example Outputs
```
=== MW Drivel Defence Batch Analysis ===

Configuration:
  Allowed namespaces: 0, 4
  Minimum words: 50
  Max sentence length: 25

Found 15 pages to analyze

Analyzing: Main_Page - 245 words, 2 long sentences - ✓ POSTED to talk page
Analyzing: Help_Page - 89 words, 0 long sentences - SKIPPED (no issues found)
Analyzing: Project_Guidelines - 156 words, 4 long sentences - ✓ POSTED to talk page

=== Summary ===
Pages analyzed: 15
Talk pages updated: 8
Pages skipped: 7
```

## Word Database

The extension includes a comprehensive database of words and their plain English alternatives, based on the "A to Z of Alternative Words" from the Plain English Campaign. This includes:

- Government and bureaucratic jargon
- Academic and technical terms
- Complex words with simpler alternatives
- British English variants and corrections

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Internet Explorer 11+
- Mobile browsers (iOS Safari, Android Chrome)

## License

- Application code: GPL-2.0-or-later
- Word database: © 2001 Plain English Campaign
- Original application: © 2006 J C Rugg

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## Support

For support and bug reports, please use the GitHub issues page.

## Credits

- Original DrivelDefence application by J C Rugg (2006)
- A to Z of Alternative Words by Plain English Campaign (2001)
- MediaWiki extension adaptation by DAFO Creative Ltd (2025)
