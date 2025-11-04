# Publishing Guide

This guide explains how to publish `saeedvir/laravel-modules` to GitHub and Packagist.

## Prerequisites

- [x] Git initialized
- [x] GitHub account ready
- [x] Package optimized and tested
- [ ] GitHub repository created
- [ ] Packagist account ready

## Step 1: Prepare the Repository

### 1.1 Review Files

Ensure these files are properly configured:

- [x] `composer.json` - Package name is `saeedvir/laravel-modules`
- [x] `README.md` - Updated with package information
- [x] `LICENSE.md` - MIT License with your copyright
- [x] `.gitignore` - Excludes vendor, backups, and IDE files
- [x] `CHANGELOG.md` - Documents version history

### 1.2 Create Initial Commit

```bash
git add .
git commit -m "Initial commit: Optimized Laravel Modules package

- Vendor name changed from nwidart to saeedvir
- Package slimmed by 40% (removed 25 rarely-used commands)
- Removed Lumen support for cleaner Laravel-focused codebase
- Lazy loading enabled by default
- Performance optimizations applied
- Updated documentation and licensing"
```

## Step 2: Create GitHub Repository

### 2.1 On GitHub

1. Go to [GitHub](https://github.com/new)
2. Create a new repository:
   - **Name**: `laravel-modules`
   - **Description**: `Optimized Laravel package for managing modular applications - 40% smaller and faster than original`
   - **Visibility**: Public
   - **DO NOT** initialize with README, .gitignore, or license (we already have these)

### 2.2 Link Local to Remote

```bash
# Add GitHub remote (replace YOUR_GITHUB_USERNAME)
git remote add origin https://github.com/saeedvir/laravel-modules.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Step 3: Tag the Release

### 3.1 Create Version Tag

```bash
# Tag the current version
git tag -a v12.0.0 -m "Release v12.0.0 - Optimized fork

Features:
- 40% smaller package size
- Faster boot time with lazy loading
- Laravel 12.x support
- Removed rarely-used generators
- Removed Lumen support
- Performance optimizations"

# Push tag to GitHub
git push origin v12.0.0
```

## Step 4: Publish to Packagist

### 4.1 Register on Packagist

1. Go to [Packagist.org](https://packagist.org/)
2. Login or register an account
3. Click "Submit Package"
4. Enter your GitHub repository URL: `https://github.com/saeedvir/laravel-modules`
5. Click "Check"
6. Click "Submit"

### 4.2 Enable Auto-Update Hook

To automatically update Packagist when you push to GitHub:

1. On Packagist, go to your package page
2. Click "Settings" tab
3. Find "GitHub Hook" section
4. Copy the webhook URL

On GitHub:
1. Go to your repository Settings
2. Click "Webhooks" → "Add webhook"
3. Paste the Packagist webhook URL
4. Content type: `application/json`
5. Select "Just the push event"
6. Click "Add webhook"

## Step 5: Update README Badges

Update badges in README.md with correct URLs:

```markdown
[![Latest Version on Packagist](https://img.shields.io/packagist/v/saeedvir/laravel-modules.svg?style=flat-square)](https://packagist.org/packages/saeedvir/laravel-modules)
[![Total Downloads](https://img.shields.io/packagist/dt/saeedvir/laravel-modules.svg?style=flat-square)](https://packagist.org/packages/saeedvir/laravel-modules)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
```

## Step 6: Verify Installation

Test that users can install your package:

```bash
composer require saeedvir/laravel-modules
```

## Publishing Checklist

Before publishing, ensure:

- [ ] Package name in `composer.json` is correct: `saeedvir/laravel-modules`
- [ ] All tests pass
- [ ] README is comprehensive and accurate
- [ ] License file includes your copyright
- [ ] CHANGELOG is up to date
- [ ] No sensitive information in repository
- [ ] .gitignore properly excludes unnecessary files
- [ ] All documentation references updated vendor name
- [ ] Version tag follows semantic versioning (v12.0.0)

## Future Updates

### For Bug Fixes (Patch)

```bash
# Make changes
git add .
git commit -m "Fix: description of fix"
git tag -a v12.0.1 -m "Bug fix release"
git push origin main --tags
```

### For New Features (Minor)

```bash
# Make changes
git add .
git commit -m "Feature: description of feature"
git tag -a v12.1.0 -m "Feature release"
git push origin main --tags
```

### For Breaking Changes (Major)

```bash
# Make changes
git add .
git commit -m "Breaking: description of changes"
git tag -a v13.0.0 -m "Major release with breaking changes"
git push origin main --tags
```

## Maintenance

### Update Packagist Manually

If auto-update doesn't work:
1. Go to your package on Packagist
2. Click "Update" button

### Monitor Your Package

- **Packagist**: https://packagist.org/packages/saeedvir/laravel-modules
- **GitHub Issues**: Monitor and respond to user issues
- **GitHub Stars**: Track community interest

## Recommended GitHub Settings

### Topics to Add

Add these topics to your GitHub repository for better discoverability:
- `laravel`
- `laravel-package`
- `modules`
- `modular`
- `php`
- `laravel-12`
- `performance`

### Branch Protection

Consider protecting the `main` branch:
1. Settings → Branches → Add rule
2. Branch name pattern: `main`
3. Enable:
   - Require pull request reviews before merging
   - Require status checks to pass before merging

## Documentation Website (Optional)

If you want to create documentation:
1. Enable GitHub Pages in repository settings
2. Use the `/docs` folder or gh-pages branch
3. Consider using VuePress or similar for documentation

## Support & Community

Consider adding:
- **GitHub Discussions**: For community questions
- **Issue Templates**: For bug reports and feature requests
- **Contributing Guidelines**: In CONTRIBUTING.md
- **Code of Conduct**: For community standards

## Marketing Your Package

1. **Tweet about it**: Share on Twitter with #Laravel hashtag
2. **Reddit**: Post in r/laravel and r/PHP
3. **Laravel News**: Submit to Laravel News
4. **Dev.to**: Write an article about the package
5. **Laracasts Forum**: Share in the forum

## Quick Commands Reference

```bash
# Create repository and push
git remote add origin https://github.com/saeedvir/laravel-modules.git
git branch -M main
git push -u origin main

# Tag and release
git tag -a v12.0.0 -m "Release v12.0.0"
git push origin v12.0.0

# Update after changes
git add .
git commit -m "Your commit message"
git push origin main

# Create new release
git tag -a v12.0.1 -m "Release notes"
git push origin v12.0.1
```

## Need Help?

- GitHub Docs: https://docs.github.com
- Packagist Docs: https://packagist.org/about
- Composer Docs: https://getcomposer.org/doc/

---

**Ready to publish!** Follow the steps above to share your optimized Laravel Modules package with the community. 🚀
