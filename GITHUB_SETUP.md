# 📦 GitHub Repository Setup Guide

**Project**: JV Prophecy Manager  
**Version**: 1.0.2-stable  
**Status**: Ready for GitHub

---

## ✅ Local Repository Status

**Git initialized**: ✓  
**Files committed**: ✓  
**Ready to push**: ✓

---

## 🚀 Pushing to GitHub

### Option 1: Create New Repository on GitHub (Recommended)

#### Step 1: Create Repository on GitHub

1. Go to [GitHub](https://github.com)
2. Click the **"+"** icon (top right) → **"New repository"**
3. Repository details:
   - **Name**: `VSK-JV-Prophecy` (or your preferred name)
   - **Description**: `JV Prophecy Manager - Daily prophecy content management system with multilingual support`
   - **Visibility**: 
     - ✅ **Private** (Recommended for proprietary code)
     - ⬜ Public
   - **Initialize**: 
     - ⬜ Do NOT add README (we already have one)
     - ⬜ Do NOT add .gitignore (we already have one)
     - ⬜ Do NOT add license
4. Click **"Create repository"**

#### Step 2: Link Local Repository to GitHub

GitHub will show you commands. Use these (replace YOUR_USERNAME):

```bash
# Add remote
git remote add origin https://github.com/YOUR_USERNAME/VSK-JV-Prophecy.git

# Rename branch to main (if needed)
git branch -M main

# Push to GitHub
git push -u origin main
```

**Example**:
```bash
git remote add origin https://github.com/vojmedia/VSK-JV-Prophecy.git
git branch -M main
git push -u origin main
```

#### Step 3: Enter Credentials

- **Username**: Your GitHub username
- **Password**: Use **Personal Access Token** (not your password)

**Don't have a token?** Create one:
1. GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Generate new token
3. Select scopes: `repo` (full control)
4. Copy the token (save it securely!)

---

### Option 2: Using SSH (If SSH keys configured)

```bash
# Add SSH remote
git remote add origin git@github.com:YOUR_USERNAME/VSK-JV-Prophecy.git

# Push
git branch -M main
git push -u origin main
```

---

### Option 3: Using GitHub CLI (gh)

```bash
# Login
gh auth login

# Create repo and push
gh repo create VSK-JV-Prophecy --private --source=. --push

# Or if repo exists
gh repo set-remote origin
git push -u origin main
```

---

## 📋 What's Being Pushed

### Repository Contents:

**Application Files**:
- ✅ All PHP source code (`app/`, `config/`, `routes/`)
- ✅ Database migrations and seeders
- ✅ Views and templates (`resources/views/`)
- ✅ Public assets (CSS, JS, images)

**Configuration**:
- ✅ AWS Elastic Beanstalk config (`.ebextensions/`, `.platform/`)
- ✅ Composer & NPM dependencies manifests
- ✅ Environment example (`.env.example`)
- ✅ `.gitignore` (properly configured)

**Documentation**:
- ✅ README.md (comprehensive guide)
- ✅ AWS deployment guides
- ✅ SSL setup instructions
- ✅ All technical documentation

**Excluded (via .gitignore)**:
- ❌ `.env` files (sensitive data)
- ❌ `vendor/` directory (will be installed via composer)
- ❌ `node_modules/` (will be installed via npm)
- ❌ Database files (`.sqlite`)
- ❌ Deployment packages (`*.zip`)
- ❌ Logs and cache files
- ❌ IDE configurations

---

## 🔒 Security Checklist

Before pushing, verify:

- [ ] `.env` file is NOT included (check `.gitignore`)
- [ ] No database credentials in code
- [ ] No API keys or secrets in code
- [ ] No production passwords in SQL files
- [ ] `.gitignore` is properly configured
- [ ] Private files are excluded

**Check what's being pushed**:
```bash
# See all files that will be pushed
git ls-files

# Check for sensitive files
git ls-files | grep -E "\.env$|password|secret|key"
```

---

## 📊 Repository Statistics

**Commit**: 1 initial commit  
**Files**: ~500+ files  
**Size**: ~15-20 MB (without node_modules/vendor)  
**Languages**: PHP, JavaScript, Blade, CSS

---

## 🔧 Post-Push Setup

### 1. Add Collaborators (if team project)

GitHub → Repository → Settings → Collaborators → Add people

### 2. Configure Repository Settings

**Security**:
- Settings → Security → Enable vulnerability alerts
- Settings → Security → Enable automated security fixes

**Branches**:
- Settings → Branches → Add branch protection rule
- Protect `main` branch:
  - ✅ Require pull request before merging
  - ✅ Require status checks to pass

**Actions** (if using CI/CD):
- Settings → Actions → General → Allow actions
- GitHub Actions workflow already included: `.github/workflows/laravel-tests.yml`

### 3. Add Repository Secrets (for Actions)

Settings → Secrets and variables → Actions → New repository secret

Add:
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `DB_PASSWORD` (for testing)

---

## 📝 Maintaining the Repository

### Daily Workflow:

```bash
# Check status
git status

# Stage changes
git add .

# Commit with message
git commit -m "Description of changes"

# Push to GitHub
git push
```

### Creating Features:

```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "Add new feature"

# Push branch
git push -u origin feature/new-feature

# Create Pull Request on GitHub
# After review and merge, update main
git checkout main
git pull
```

### Tagging Releases:

```bash
# Create version tag
git tag -a v1.0.2 -m "Release v1.0.2 - Production ready"

# Push tag
git push origin v1.0.2

# Or push all tags
git push --tags
```

---

## 🌿 Branching Strategy

### Recommended Structure:

```
main (production-ready)
  ├── develop (active development)
  ├── feature/xyz (new features)
  ├── bugfix/abc (bug fixes)
  └── hotfix/urgent (production hotfixes)
```

### Branch Protection:

**main**:
- Protect from direct pushes
- Require pull requests
- Require code review

**develop**:
- Allow direct pushes
- Merge to main via PR

---

## 🔍 Useful Git Commands

### View History:
```bash
# See commit history
git log --oneline

# See changes in last commit
git show

# See file history
git log -p -- path/to/file
```

### Undo Changes:
```bash
# Undo uncommitted changes
git checkout -- path/to/file

# Undo last commit (keep changes)
git reset HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1
```

### Remote Management:
```bash
# See remotes
git remote -v

# Change remote URL
git remote set-url origin NEW_URL

# Remove remote
git remote remove origin
```

---

## 📦 Cloning the Repository (New Machine)

```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/VSK-JV-Prophecy.git
cd VSK-JV-Prophecy

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Start server
php artisan serve
```

---

## 🆘 Troubleshooting

### Push Rejected

**Problem**: `! [rejected] main -> main (fetch first)`

**Solution**:
```bash
git pull origin main --rebase
git push origin main
```

### Authentication Failed

**Problem**: Password authentication not working

**Solution**: Use Personal Access Token instead of password

### Large File Error

**Problem**: File exceeds GitHub's 100MB limit

**Solution**:
```bash
# Add to .gitignore
echo "large-file.ext" >> .gitignore
git rm --cached large-file.ext
git commit -m "Remove large file"
git push
```

### Wrong Files Committed

**Problem**: Accidentally committed `.env` or sensitive files

**Solution**:
```bash
# Remove from git (but keep local)
git rm --cached .env
git commit -m "Remove sensitive file"

# Make sure .gitignore includes it
echo ".env" >> .gitignore
git add .gitignore
git commit -m "Update gitignore"
git push
```

If already pushed to GitHub:
1. Change all passwords/keys immediately!
2. Consider repository as compromised
3. Rotate all secrets
4. Use `git filter-branch` or BFG Repo-Cleaner to remove from history

---

## 📞 Support

### GitHub Resources:
- [GitHub Docs](https://docs.github.com)
- [Git Handbook](https://guides.github.com/introduction/git-handbook/)
- [GitHub Actions](https://docs.github.com/en/actions)

### Project Contact:
- **Email**: vojmedia@gmail.com
- **Repository**: (will be available after push)

---

## ✅ Quick Checklist

**Before Pushing**:
- [ ] .env is in .gitignore
- [ ] All sensitive data excluded
- [ ] README.md is comprehensive
- [ ] .gitignore is complete

**After Creating Repository**:
- [ ] Add remote origin
- [ ] Push main branch
- [ ] Add collaborators (if any)
- [ ] Configure branch protection
- [ ] Add repository description
- [ ] Add topics/tags

**Ongoing**:
- [ ] Regular commits
- [ ] Meaningful commit messages
- [ ] Use branches for features
- [ ] Create releases/tags
- [ ] Keep documentation updated

---

**Ready to Push**: 🚀 YES!

**Next Command**:
```bash
git remote add origin https://github.com/YOUR_USERNAME/VSK-JV-Prophecy.git
git push -u origin main
```

---

**Created**: October 19, 2025  
**Version**: 1.0  
**Status**: Repository Ready

