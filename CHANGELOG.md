# Changelog

All notable changes to the DrivelDefence MediaWiki extension will be documented in this file.

## [1.0.0] - 2025-07-01

### Added
- Initial release as MediaWiki extension
- Special page for plain English text analysis
- Parser function for inline text checking
- **Automatic talk page analysis**: Posts plain English analysis to talk pages when pages are saved
- Word database with 1000+ alternative word suggestions
- Sentence analysis with word count reporting
- Responsive web design
- Internationalization support
- Integration with MediaWiki resource loader
- Accessibility features
- Configuration options for automatic analysis (namespaces, thresholds, etc.)

### Converted
- Original HTML/JavaScript application to MediaWiki extension
- Standalone CSS to MediaWiki-compatible styles
- JavaScript functions to work with MediaWiki environment
- Static word database to PHP/JavaScript integration

### Features
- Analysis of text for plain English compliance
- Highlighting of words with simpler alternatives
- Sentence length analysis and reporting
- Statistical summaries (word count, sentence count, averages)
- Options for different reporting levels
- Mobile-friendly responsive design
- **Talk page integration**: Automatic posting of analysis results with recommendations

## Legacy Versions

### DrivelDefenceWeb1v0.html - 2006-11-11
- Original web page checking application by J Rugg
- Analysis of HTML content for plain English usage

### DrivelDefenceText1v0.html - 2006-11-13  
- Plain text checking application by J Rugg
- Text-only analysis functionality

### DrivelDefenceshared.js - 2006-10-11
- Shared JavaScript library with word database
- Core analysis functions
- Based on Plain English Campaign's "A to Z of Alternative Words" (2001)
