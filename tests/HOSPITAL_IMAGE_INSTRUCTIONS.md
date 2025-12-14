# Hospital Background Image Instructions

## Image File Location
Place your hospital building image at: `public/images/hospital-building.jpg`

## Supported Formats
- JPG/JPEG (recommended)
- PNG
- WebP

## Image Requirements
- Recommended size: 1920x1080 or larger
- File name: `hospital-building.jpg` (or `.png`, `.webp`)

## To Add Your Image

1. **Save the hospital building image** to:
   ```
   public/images/hospital-building.jpg
   ```

2. **If using a different format**, update the CSS in `app/Views/templates/layout.php`:
   - Change `hospital-building.jpg` to `hospital-building.png` (or your format)

3. **The image will automatically appear** as the background on:
   - Home page
   - Login page

## Current Setup
The background is set up with:
- Hospital building image as background
- Purple gradient overlay (85% opacity) for readability
- Fixed attachment for parallax effect
- Login form remains clearly visible

The login form card will appear on top of the background image with a white background, ensuring it's always readable.

