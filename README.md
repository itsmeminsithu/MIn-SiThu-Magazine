# Min SiThu Magazine WordPress Theme

WordPress magazine theme built for **minsithu.org**. Blue and white colours based on Facebook's blue, plain cards in the style of Apple Finder, a font stack that handles Myanmar Unicode, and SEO/social preview tags. It runs on WordPress, so there is no live GitHub preview.

**Current version: 3.4 — Social Preview Final**

## Details

- Primary blue: `#1877F2`
- White / light-blue card layout
- Responsive layout, with a mobile header that stays put
- Myanmar Unicode-ready font stack
- Local SVG icons, no icon font or CDN
- SEO and social preview tags
- Featured image used as the preview on Facebook, X, Reddit, LinkedIn, Telegram, Messenger, and other platforms
- Placeholder images for posts with no featured photo
- Author card showing **MIN SITHU**
- Typewriter text in the header, in a software engineer style
- Social links: GitHub, Facebook, Medium, LinkedIn, Slack, X, YouTube
- Favicon and Apple touch icon
- Comment nonce and honeypot protection
- Output escaped and input sanitised in the theme code

## Install

1. Download the theme ZIP or clone this repository.
2. In WordPress Admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the theme ZIP.
4. Activate **Min SiThu Magazine**.
5. Go to **Customize → Site Identity** and set the logo / site icon.
6. Go to **Customize → Min SiThu Theme** and add social links.

## Social preview setup

1. Set a **Featured Image** for each article.
2. Use images around **1200 × 630 px** where you can.
3. After publishing, refresh the link preview with:
   - Facebook Sharing Debugger
   - X/Twitter Card Validator
   - LinkedIn Post Inspector
   - Reddit: repost or clear the cache where the platform allows it

If an article has no featured image, the theme uses:

`assets/images/social-preview-default.png`

## Check the PHP

```bash
find . -name "*.php" -print0 | xargs -0 -n1 php -l
```

## Author

**Min SiThu** — https://minsithu.org

## License

GPL-2.0-or-later
