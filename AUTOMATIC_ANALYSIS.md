# MW Drivel Defence - Automatic Analysis Documentation

## Overview

The MW Drivel Defence extension includes powerful automatic analysis functionality that can analyze pages when they are saved and automatically post the results to the page's discussion (talk) page. This feature provides continuous monitoring of content quality and accessibility compliance.

## How Automatic Analysis Works

### 1. **Trigger Mechanism**
- Analysis is triggered when a page is saved (using MediaWiki's `PageContentSaveComplete` hook)
- Only analyzes pages in specified namespaces (configurable)
- Respects minimum word count thresholds
- Does not analyze talk pages themselves (prevents recursion)

### 2. **Analysis Process**
The extension performs comprehensive analysis including:

#### Plain English Analysis:
- Word count and sentence analysis
- Long sentence detection (configurable threshold)
- Alternative word suggestions (based on Plain English Campaign guidelines)
- Average words per sentence calculation

#### WCAG 2.2 AA Accessibility Analysis:
- **Reading Level Metrics**:
  - Flesch-Kincaid Grade Level (target: 8 or below)
  - Flesch Reading Ease (target: 60 or above) 
  - Gunning Fog Index (target: 12 or below)
- **Dyslexia-Friendly Analysis**:
  - Long paragraph detection (40+ words)
  - Complex sentence identification (20+ words or high syllable density)
  - Overall dyslexia-friendliness scoring
- **Overall Accessibility Score**: 0-100 rating with specific recommendations

### 3. **Talk Page Integration**
- Automatically creates talk pages if they don't exist
- Adds a new section with timestamped analysis results
- Includes both plain English and accessibility analysis
- Provides specific, actionable recommendations
- Links back to the analysis tool for manual checking

## Configuration

### Basic Configuration (LocalSettings.php)

```php
# Enable automatic analysis when pages are saved
$wgMWDrivelDefenceAutoAnalyze = true;

# Namespaces to analyze (0=Main, 4=Project, etc.)
$wgMWDrivelDefenceNamespaces = [0, 4];

# Minimum word count to trigger analysis
$wgMWDrivelDefenceMinWords = 50;

# Maximum recommended sentence length
$wgMWDrivelDefenceMaxSentenceLength = 25;
```

### Available Configuration Options

| Setting | Default | Description |
|---------|---------|-------------|
| `MWDrivelDefenceAutoAnalyze` | `false` | Enable/disable automatic analysis |
| `MWDrivelDefenceNamespaces` | `[0, 4]` | Array of namespace IDs to analyze |
| `MWDrivelDefenceMinWords` | `50` | Minimum words to trigger analysis |
| `MWDrivelDefenceMaxSentenceLength` | `25` | Sentence length threshold |

### Namespace Reference
- `0` = Main (articles)
- `1` = Talk (article discussions)
- `2` = User (user pages)
- `3` = User talk
- `4` = Project (project pages)
- `5` = Project talk
- `6` = File (file description pages)
- `8` = MediaWiki (system messages)
- `10` = Template
- `12` = Help
- `14` = Category

## Talk Page Output Example

When analysis finds issues, it posts content like this to the talk page:

```
== Plain English & Accessibility Analysis - Wed, 16 Oct 2025 10:30:25 +0000 ==

This page was automatically analyzed for plain English compliance and WCAG 2.2 AA accessibility on Wed, 16 Oct 2025 10:30:25 +0000. Here are the results:

'''Plain English Summary:'''
* Total words: 245
* Total sentences: 12
* Average words per sentence: 20.4
* Sentences over 25 words: 3
* Words with simpler alternatives: 8

'''Plain English Recommendations:'''
⚠ Found 3 long sentences and 8 words that could be simplified.

'''WCAG 2.2 AA Accessibility Analysis:'''
* Overall Accessibility Score: 72/100
* Reading Level:
** Flesch-Kincaid Grade Level: 11.2 (target: 8 or below)
** Flesch Reading Ease: 45.3 (target: 60 or above)
** Gunning Fog Index: 13.8 (target: 12 or below)
* Dyslexia-Friendly Analysis:
** Long paragraphs (40+ words): 2/5
** Complex sentences: 5/12

'''Accessibility Recommendations:'''
• Simplify language to reduce reading grade level
• Use shorter sentences and simpler words to improve readability
• Break long paragraphs into shorter sections (3-4 sentences max)
⚠ Content partially meets accessibility standards - improvements recommended

This accessibility analysis helps ensure content is readable by users with disabilities, including those who use screen readers or have dyslexia.

This analysis was generated automatically by the [[Special:MWDrivelDefence|Plain English & Accessibility Checker]] extension.
```

## Best Practices

### 1. **Selective Enablement**
- Start with a limited namespace (e.g., just main articles)
- Monitor the impact before expanding to more namespaces
- Consider your wiki's content volume and user base

### 2. **Community Guidelines**
- Inform your community about the automatic analysis
- Create guidelines for responding to analysis results
- Train editors on accessibility principles

### 3. **Threshold Tuning**
- Adjust `MWDrivelDefenceMinWords` based on your content
- Modify `MWDrivelDefenceMaxSentenceLength` for your audience
- Monitor false positives and adjust as needed

### 4. **Talk Page Management**
- Consider archiving old analysis results periodically
- Encourage editors to address issues and mark them as resolved
- Use the analysis as a starting point for improvement discussions

## Technical Details

### Performance Considerations
- Analysis only runs on page saves, not views
- Lightweight text processing algorithms
- Configurable thresholds prevent unnecessary analysis
- Error handling prevents analysis failures from breaking page saves

### Permissions
- Uses the same permissions as the user saving the page
- Creates talk pages with appropriate permissions
- Logs errors to MediaWiki debug log for troubleshooting

### Extensibility
- Hook system allows other extensions to modify analysis
- Modular design enables easy enhancement
- Internationalization support for multilingual wikis

## Troubleshooting

### Common Issues

**Analysis not running:**
- Check `$wgMWDrivelDefenceAutoAnalyze` is set to `true`
- Verify page is in an allowed namespace
- Ensure page meets minimum word count
- Check MediaWiki debug logs for errors

**Talk page not created:**
- Verify permissions for the saving user
- Check for namespace restrictions on talk pages
- Look for errors in the debug log

**Missing analysis sections:**
- Ensure both plain English and accessibility thresholds are met
- Check that analysis found actual issues to report
- Verify i18n messages are properly loaded

### Debug Mode
Enable debug logging in LocalSettings.php:
```php
$wgDebugLogGroups['MWDrivelDefence'] = '/path/to/mwdriveldefence.log';
```

## Future Enhancements

### Planned Features
- Batch analysis of existing pages
- Email notifications for analysis results
- Integration with MediaWiki's notification system
- Advanced accessibility checks (color contrast, etc.)
- Customizable analysis templates

### Community Features
- Analysis result voting/rating system
- Collaborative improvement tracking
- Progress dashboards for accessibility compliance
- Integration with page quality scores

## Conclusion

The automatic analysis feature transforms MW Drivel Defence from a manual tool into a comprehensive content quality monitoring system. By providing continuous feedback on both plain English usage and accessibility compliance, it helps wikis maintain high-quality, inclusive content that serves all users effectively.

The combination of automated analysis with manual tools provides a complete solution for organizations needing to meet accessibility standards while maintaining readable, user-friendly content.