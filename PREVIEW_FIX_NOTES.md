# Fix Preview Modal - November 5, 2025

## Issues Fixed

### 1. ✅ Internal Server Error - BinaryFileResponse::header()

**Problem:**

```
Call to undefined method Symfony\Component\HttpFoundation\BinaryFileResponse::header()
```

**Root Cause:**
Middleware `PreventBackHistory` was calling `header()` method on all responses, but `BinaryFileResponse` (returned by `response()->file()`) doesn't have this method.

**Solution:**
Modified `app/Http/Middleware/PreventBackHistory.php` to **skip cache headers for file responses**:

```php
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);

    // Skip cache control headers for file responses (BinaryFileResponse)
    // because PDF viewers and file previews need caching to work properly
    // Security is already handled by signed URLs with expiration
    if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
        return $response;
    }

    // Apply no-cache headers only to HTML responses
    return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
}
```

**Why This Fix Works:**

-   PDF viewers in browsers **require caching** to display files in iframes
-   `no-cache` headers prevent browser from caching the file, breaking PDF display
-   Security is maintained through **signed URLs with 30-minute expiration**
-   Only HTML pages get no-cache headers (to prevent back button issues)

### 2. ✅ Modal Not Centered & Title Not Visible

**Problems:**

-   Modal tidak berada di tengah layar
-   Nama file tidak terlihat di header modal
-   Layout tidak responsif dengan baik

**Solutions:**

#### A. Fixed Modal Container Layout

Changed from:

```html
<div class="min-h-screen flex items-center justify-center p-4"></div>
```

To:

```html
<div class="h-screen w-screen flex items-center justify-center p-4"></div>
```

#### B. Fixed Header Flex Layout

-   Added `shrink-0` to icon and button to prevent shrinking
-   Added `flex-1 min-w-0` to title container for proper text truncation
-   Added `ml-4` to close button for spacing
-   Changed title to use `truncate` class for long filenames

```html
<div class="flex items-center gap-3">
    <div class="w-10 h-10 ... shrink-0">...</div>
    <div class="flex-1 min-w-0">
        <h3 class="... truncate" id="previewModalTitle">Preview Dokumen</h3>
        <p class="...">Klik di luar atau tekan ESC untuk menutup</p>
    </div>
</div>
<button ... class="... shrink-0 ml-4">...</button>
```

#### C. Removed Conflicting Classes

-   Removed `hidden` class from modal (kept only inline `style="display: none;"`)
-   This prevents CSS conflicts between Tailwind and inline styles

### 3. ✅ Tailwind CSS Lint Fix

Fixed one more deprecated class:

```
bg-gradient-to-br → bg-linear-to-br
```

## Testing Checklist

Please test the following scenarios:

### Preview Functionality

-   [ ] Click "Preview" button on PDF file → Modal opens centered with PDF viewer
-   [ ] Click "Preview" button on image file → Modal opens centered with image display
-   [ ] Click "Preview" button on other file type → Modal shows "Open in New Tab" message
-   [ ] Long filename in title → Text truncates with ellipsis (...)
-   [ ] Modal is centered on screen (not offset)
-   [ ] Modal header is fully visible with all elements

### Modal Controls

-   [ ] Click close button (X) → Modal closes
-   [ ] Click outside modal → Modal closes
-   [ ] Press ESC key → Modal closes
-   [ ] Body scroll is disabled when modal open
-   [ ] Body scroll is restored when modal closes

### Download Functionality

-   [ ] Click "Download" button → File downloads with correct name
-   [ ] Download works for all file types

### Responsive Design

-   [ ] Modal displays correctly on desktop (1920x1080)
-   [ ] Modal displays correctly on tablet (768x1024)
-   [ ] Modal displays correctly on mobile (375x667)

### Edge Cases

-   [ ] Preview expired signed URL (after 30 min) → Shows error message
-   [ ] Preview deleted file → Shows 404 error
-   [ ] Preview very large PDF (>10MB) → Loads without timeout
-   [ ] Preview corrupted image → Shows fallback error message

## Files Modified

1. `app/Http/Middleware/PreventBackHistory.php` - Skip cache headers for file responses (allows PDF preview)
2. `app/Http/Controllers/KepalaSekolah/EvaluasiController.php` - Added cache headers to file response
3. `resources/views/kepala/evaluasi/show.blade.php` - Fixed modal layout and centering

## Technical Details

### File Response Headers

Modified `previewDocument()` method to include proper caching headers:

```php
return response()->file($filePath, [
    'Content-Type' => $mimeType,
    'Content-Disposition' => 'inline; filename="..."',
    'X-Content-Type-Options' => 'nosniff',
    'Cache-Control' => 'private, max-age=3600', // Cache for 1 hour
]);
```

This allows:

-   Browser to cache file for better performance
-   PDF viewer to work properly in iframe
-   Security maintained through signed URLs

## Build Status

✅ Assets compiled successfully:

```
✓ built in 2.27s
public/build/assets/app-g_RDtlyy.css  152.29 kB │ gzip: 20.25 kB
public/build/assets/app-DC5mxhBw.js   118.58 kB │ gzip: 39.26 kB
```

## Notes

-   Signed URLs expire after 30 minutes (configured in view)
-   Preview uses `response()->file()` with `Content-Disposition: inline`
-   Download uses `response()->download()` with original filename
-   Modal max height is 90vh to prevent overflow on small screens
-   Dark mode fully supported

## Next Steps

1. Test all functionality in browser
2. If issues persist, check browser console for JavaScript errors
3. Verify file permissions on storage directory
4. Check Laravel logs: `tail -f storage/logs/laravel.log`
