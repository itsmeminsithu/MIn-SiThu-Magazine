# Min SiThu Magazine WordPress Theme

**Min SiThu Magazine** is a clean, fast, responsive WordPress magazine theme for **minsithu.org**.

It uses a Facebook-inspired blue/white color system, Apple Finder-style clean cards, Myanmar Unicode typography support, SEO/social preview metadata, security-safe WordPress patterns, and a premium author profile.

## Current Version

**3.4 — Social Preview Final**

## Highlights

- Facebook-style primary blue: `#1877F2`
- Clean white/light-blue Apple Finder-style UI
- Mobile responsive layout
- Stable mobile header
- Myanmar Unicode-ready font stack
- Fast local SVG icons
- SEO and social preview tags
- Featured image preview for Facebook, X, Reddit, LinkedIn, Telegram, Messenger, and other platforms
- Default social preview image fallback
- Auto placeholder images for posts without featured photos
- Premium author card showing **MIN SITHU**
- Looping Software Engineer-style typewriter text
- GitHub, Facebook, Medium, LinkedIn, Slack, X, and YouTube social support
- Favicon and Apple touch icon support
- Comment nonce and honeypot protection
- Safer escaping and sanitization

## Installation

1. Download the theme ZIP or clone this repository.
2. In WordPress Admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the theme ZIP.
4. Activate **Min SiThu Magazine**.
5. Go to **Customize → Site Identity** and set logo/site icon.
6. Go to **Customize → Min SiThu Theme** and add social links.

## Social Preview Setup

For best sharing previews:

1. Set a **Featured Image** for each article.
2. Use images around **1200 × 630 px** when possible.
3. After publishing, refresh link previews using:
   - Facebook Sharing Debugger
   - X/Twitter Card Validator
   - LinkedIn Post Inspector
   - Reddit/link preview refresh by reposting or cache-clearing where available

If an article has no featured image, the theme uses:

`assets/images/social-preview-default.png`

## Developer Checks

```bash
find . -name "*.php" -print0 | xargs -0 -n1 php -l
node -c assets/js/main.js
```

## Suggested GitHub Repo

Repo name:

```text
minsithu-magazine-theme
```

Description:

```text
Clean Facebook-blue and Apple Finder-style WordPress magazine theme by Min SiThu.
```

## Push to GitHub

```bash
git remote add origin https://github.com/itsmeminsithu/minsithu-magazine-theme.git
git branch -M main
git push -u origin main
```

## Author

**Min SiThu**

Website: <https://minsithu.org>

## License

GPL-2.0-or-later
