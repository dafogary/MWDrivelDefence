# MW Drivel Defence - Installation Verification

## Issues Fixed

### 1. Deprecated setPageTitle Method ✅
**Problem**: `Use of MediaWiki\Output\OutputPage::setPageTitle with Message argument was deprecated in MediaWiki 1.41`

**Solution**: Changed from:
```php
$out->setPageTitle( $this->msg( 'mwdriveldefence-title' ) );
```
To:
```php
$out->setPageTitle( $this->msg( 'mwdriveldefence-title' )->text() );
```

### 2. Special Page Registration ✅
**Problem**: Special page not appearing on Special:SpecialPages

**Solution**: Made several improvements:
1. Changed group from `'other'` to `'wiki'` for better visibility
2. Added proper description method
3. Updated alias file with correct special page name
4. Added `special-mwdriveldefence` message for listing

## Files Modified

### 1. `/includes/SpecialMWDrivelDefence.php`
- Fixed deprecated `setPageTitle` method
- Changed `getGroupName()` from `'other'` to `'wiki'`
- Added `getDescription()` method

### 2. `/MWDrivelDefence.alias.php`
- Updated special page name from `'DrivelDefence'` to `'MWDrivelDefence'`
- Added `'AccessibilityChecker'` as an additional alias

### 3. `/i18n/en.json`
- Added `"special-mwdriveldefence"` message for special page listing

## Verification Steps

1. **Check for Deprecation Warning**:
   - Visit `Special:MWDrivelDefence` on your wiki
   - The deprecation warning should no longer appear

2. **Verify Special Page Listing**:
   - Go to `Special:SpecialPages` on your wiki
   - Look for "Plain English & Accessibility Checker" in the "Wiki data and tools" section
   - Click on it to ensure it loads correctly

3. **Test Aliases**:
   - Try accessing via different URLs:
     - `Special:MWDrivelDefence`
     - `Special:PlainEnglishChecker`
     - `Special:AccessibilityChecker`

4. **Functionality Test**:
   - Enter sample text in the form
   - Enable accessibility analysis options
   - Click "Analyze Text" to verify full functionality

## Expected Results

- ✅ No deprecation warnings in logs
- ✅ Special page appears in `Special:SpecialPages` under "Wiki data and tools"
- ✅ All aliases work correctly
- ✅ Accessibility analysis functions properly
- ✅ Color contrast checker works
- ✅ WCAG compliance scoring displays correctly

## If Issues Persist

1. **Clear MediaWiki Cache**:
   ```bash
   php maintenance/update.php
   ```

2. **Check Extension Loading**:
   - Verify `wfLoadExtension( 'MWDrivelDefence' );` is in `LocalSettings.php`
   - Check `Special:Version` to confirm extension is loaded

3. **Permissions**:
   - Ensure your user has appropriate permissions to access special pages

4. **Browser Cache**:
   - Clear browser cache and hard refresh (Ctrl+F5)

## Success Indicators

When everything is working correctly, you should see:
- Special page loads without warnings
- Comprehensive accessibility analysis interface
- Interactive color contrast checker
- WCAG 2.2 AA compliance scoring
- No JavaScript errors in browser console