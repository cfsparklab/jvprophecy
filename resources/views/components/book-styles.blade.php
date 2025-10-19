{{-- Professional Book-Style Reading Themes --}}
<style>
/* ===================================
   COLOR SCHEMES / THEMES
   =================================== */

/* Classic Aged Paper (Default) */
.theme-classic {
    --bg-color: #f4f1ea;
    --bg-lines: #e8e4d9;
    --bg-subtle: #f9f6ef;
    --page-bg-start: #f9f6ef;
    --page-bg-mid: #fcfaf5;
    --page-bg-end: #f9f6ef;
    --border-color: #e8dcc8;
    --text-color: #2d2820;
    --heading-color: #5a4a2f;
    --accent-color: #c9a961;
    --accent-dark: #8b6914;
    --quote-bg: rgba(201, 169, 97, 0.08);
    --shadow-color: rgba(0, 0, 0, 0.1);
}

/* Sepia / Old Book */
.theme-sepia {
    --bg-color: #e8dcc8;
    --bg-lines: #d4c5b0;
    --bg-subtle: #f0e6d6;
    --page-bg-start: #f0e6d6;
    --page-bg-mid: #f5ede0;
    --page-bg-end: #f0e6d6;
    --border-color: #c9b89a;
    --text-color: #3d3020;
    --heading-color: #5d4a2f;
    --accent-color: #a67c52;
    --accent-dark: #7a5530;
    --quote-bg: rgba(166, 124, 82, 0.1);
    --shadow-color: rgba(61, 48, 32, 0.15);
}

/* Dark Parchment */
.theme-dark {
    --bg-color: #2d2820;
    --bg-lines: #3d3530;
    --bg-subtle: #3a342d;
    --page-bg-start: #3a342d;
    --page-bg-mid: #42392f;
    --page-bg-end: #3a342d;
    --border-color: #54493d;
    --text-color: #e8dcc8;
    --heading-color: #d4c5b0;
    --accent-color: #d4a574;
    --accent-dark: #f0e6d6;
    --quote-bg: rgba(212, 165, 116, 0.1);
    --shadow-color: rgba(0, 0, 0, 0.3);
}

/* Clean White / Modern */
.theme-modern {
    --bg-color: #f8f9fa;
    --bg-lines: #e9ecef;
    --bg-subtle: #ffffff;
    --page-bg-start: #ffffff;
    --page-bg-mid: #fefefe;
    --page-bg-end: #ffffff;
    --border-color: #dee2e6;
    --text-color: #212529;
    --heading-color: #495057;
    --accent-color: #667eea;
    --accent-dark: #4c63d2;
    --quote-bg: rgba(102, 126, 234, 0.08);
    --shadow-color: rgba(0, 0, 0, 0.08);
}

/* Cream / Kindle */
.theme-kindle {
    --bg-color: #fbf8f3;
    --bg-lines: #f0ebe0;
    --bg-subtle: #fffdf8;
    --page-bg-start: #fffdf8;
    --page-bg-mid: #fffefb;
    --page-bg-end: #fffdf8;
    --border-color: #e8e3d8;
    --text-color: #1a1a1a;
    --heading-color: #2d2d2d;
    --accent-color: #aa8f5f;
    --accent-dark: #8b7245;
    --quote-bg: rgba(170, 143, 95, 0.06);
    --shadow-color: rgba(0, 0, 0, 0.06);
}

/* Blue Tint / Professional */
.theme-professional {
    --bg-color: #f0f4f8;
    --bg-lines: #d9e2ec;
    --bg-subtle: #f8fafc;
    --page-bg-start: #f8fafc;
    --page-bg-mid: #ffffff;
    --page-bg-end: #f8fafc;
    --border-color: #cbd5e0;
    --text-color: #2d3748;
    --heading-color: #1a202c;
    --accent-color: #4299e1;
    --accent-dark: #2b6cb0;
    --quote-bg: rgba(66, 153, 225, 0.08);
    --shadow-color: rgba(0, 0, 0, 0.08);
}

/* ===================================
   PAPER TEXTURE BACKGROUND
   =================================== */
.paper-background {
    background-color: var(--bg-color);
    background-image: 
        linear-gradient(90deg, transparent 79px, var(--bg-lines) 79px, var(--bg-lines) 81px, transparent 81px),
        linear-gradient(var(--bg-subtle) 0.1em, transparent 0.1em);
    background-size: 100% 1.5em;
    position: relative;
    transition: background-color 0.3s ease;
}

.paper-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4'%3E%3Cpath fill='%23000000' fill-opacity='0.02' d='M0 0h2v2H0zm2 2h2v2H2z'/%3E%3C/svg%3E");
    pointer-events: none;
}

/* ===================================
   BOOK PAGE STYLE
   =================================== */
.book-page {
    background: linear-gradient(to right, 
        var(--page-bg-start) 0%, 
        var(--page-bg-mid) 50%, 
        var(--page-bg-end) 100%
    );
    box-shadow: 
        0 2px 10px var(--shadow-color),
        inset 0 0 80px rgba(0, 0, 0, 0.03),
        inset 0 0 30px rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
}

/* ===================================
   READING CONTENT
   =================================== */
.reading-content {
    font-family: 'Merriweather', 'Georgia', 'Times New Roman', serif;
    font-size: 1.125rem;
    line-height: 1.9;
    color: var(--text-color);
    text-align: justify;
    hyphens: auto;
    transition: color 0.3s ease;
}

/* Drop cap - first letter */
.reading-content p:first-of-type::first-letter {
    font-size: 4.5rem;
    line-height: 0.85;
    float: left;
    font-weight: 700;
    margin: 0.1em 0.15em 0 0;
    color: var(--accent-dark);
    text-shadow: 2px 2px 4px var(--shadow-color);
}

/* Headings */
.reading-content h1, .reading-content h2, .reading-content h3 {
    font-family: 'Cinzel', 'Georgia', serif;
    color: var(--heading-color);
    text-align: center;
    margin: 2rem 0 1.5rem 0;
    letter-spacing: 0.05em;
    transition: color 0.3s ease;
}

/* Quotes/blockquotes */
.reading-content blockquote {
    border-left: 4px solid var(--accent-color);
    background: var(--quote-bg);
    padding: 1.5rem 2rem;
    margin: 2rem 0;
    font-style: italic;
    border-radius: 4px;
    transition: all 0.3s ease;
}

/* ===================================
   PAGE CURL EFFECT
   =================================== */
.page-curl {
    position: relative;
}

.page-curl::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, transparent 50%, var(--shadow-color) 50%);
    box-shadow: -2px -2px 10px var(--shadow-color);
}

/* ===================================
   THEME COLORS
   =================================== */
.theme-accent {
    color: var(--accent-color) !important;
}

.theme-accent-dark {
    color: var(--accent-dark) !important;
}

.theme-heading {
    color: var(--heading-color) !important;
}

.theme-text {
    color: var(--text-color) !important;
}

.theme-border {
    border-color: var(--accent-color) !important;
}

.theme-bg-accent {
    background: var(--quote-bg) !important;
}

/* ===================================
   PRINT STYLES
   =================================== */
@media print {
    .paper-background::before {
        display: none;
    }
    
    .page-curl::after {
        display: none;
    }
    
    .book-page {
        box-shadow: none;
        border: 1px solid var(--border-color);
    }
    
    body {
        background: white !important;
    }
}

/* ===================================
   MOBILE RESPONSIVE
   =================================== */
@media (max-width: 768px) {
    .reading-content {
        font-size: 1rem;
        line-height: 1.8;
    }
    
    .reading-content p:first-of-type::first-letter {
        font-size: 3.5rem;
    }
    
    .book-page {
        padding: 2rem 1.5rem !important;
    }
}
</style>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">

{{-- Theme Switcher Button --}}
<div id="theme-switcher" style="position: fixed; top: 100px; right: 20px; z-index: 1000; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); padding: 12px; display: none;">
    <div style="font-size: 0.75rem; font-weight: 600; color: #666; margin-bottom: 8px; text-align: center;">READING MODE</div>
    <div style="display: flex; flex-direction: column; gap: 8px;">
        <button onclick="setTheme('theme-classic')" class="theme-btn" title="Classic Aged Paper">
            <span style="background: #f4f1ea; width: 24px; height: 24px; display: inline-block; border-radius: 4px; border: 2px solid #e8dcc8;"></span>
            <span style="font-size: 0.75rem;">Classic</span>
        </button>
        <button onclick="setTheme('theme-sepia')" class="theme-btn" title="Sepia / Old Book">
            <span style="background: #e8dcc8; width: 24px; height: 24px; display: inline-block; border-radius: 4px; border: 2px solid #c9b89a;"></span>
            <span style="font-size: 0.75rem;">Sepia</span>
        </button>
        <button onclick="setTheme('theme-dark')" class="theme-btn" title="Dark Mode">
            <span style="background: #2d2820; width: 24px; height: 24px; display: inline-block; border-radius: 4px; border: 2px solid #54493d;"></span>
            <span style="font-size: 0.75rem;">Dark</span>
        </button>
        <button onclick="setTheme('theme-kindle')" class="theme-btn" title="Kindle Style">
            <span style="background: #fbf8f3; width: 24px; height: 24px; display: inline-block; border-radius: 4px; border: 2px solid #e8e3d8;"></span>
            <span style="font-size: 0.75rem;">Kindle</span>
        </button>
        <button onclick="setTheme('theme-modern')" class="theme-btn" title="Modern Clean">
            <span style="background: #ffffff; width: 24px; height: 24px; display: inline-block; border-radius: 4px; border: 2px solid #dee2e6;"></span>
            <span style="font-size: 0.75rem;">Modern</span>
        </button>
        <button onclick="setTheme('theme-professional')" class="theme-btn" title="Professional Blue">
            <span style="background: #f0f4f8; width: 24px; height: 24px; display: inline-block; border-radius: 4px; border: 2px solid #cbd5e0;"></span>
            <span style="font-size: 0.75rem;">Pro</span>
        </button>
    </div>
</div>

<style>
.theme-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.theme-btn:hover {
    border-color: #667eea;
    transform: translateX(-4px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.theme-btn.active {
    border-color: #667eea;
    background: #f0f4ff;
}

/* Toggle button */
#theme-toggle-btn {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 1001;
    background: white;
    border: none;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
}

#theme-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

@media print {
    #theme-switcher,
    #theme-toggle-btn {
        display: none !important;
    }
}
</style>

<script>
// Theme switcher functionality
function setTheme(themeName) {
    const body = document.body;
    body.className = body.className.replace(/theme-\w+/g, '');
    body.classList.add(themeName);
    
    // Save preference
    localStorage.setItem('prophecy-theme', themeName);
    
    // Update active button
    document.querySelectorAll('.theme-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.theme-btn').classList.add('active');
}

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('prophecy-theme') || 'theme-classic';
    document.body.classList.add(savedTheme);
    
    // Show theme switcher after page loads
    setTimeout(() => {
        document.getElementById('theme-switcher').style.display = 'block';
    }, 500);
    
    // Set active button
    const buttons = document.querySelectorAll('.theme-btn');
    buttons.forEach((btn, index) => {
        if (btn.getAttribute('onclick').includes(savedTheme)) {
            btn.classList.add('active');
        }
    });
});

// Toggle theme switcher
function toggleThemeSwitcher() {
    const switcher = document.getElementById('theme-switcher');
    switcher.style.display = switcher.style.display === 'none' ? 'block' : 'none';
}
</script>

<!-- Theme toggle button -->
<button id="theme-toggle-btn" onclick="toggleThemeSwitcher()" title="Change Reading Mode">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="5"/>
        <path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
    </svg>
</button>
```
