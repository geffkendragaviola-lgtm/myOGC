# OGC Frontend Styling Reference

## Stack
- Tailwind CSS (via CDN: `https://cdn.tailwindcss.com`)
- Bootstrap 5.3.0 (via CDN)
- Font Awesome 6.4.0 (icons)
- Custom CSS variables + component classes in `<style>` blocks inside layout files

---

## Typography

**Font Stack**
```css
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
```
Applied globally via `* { font-family: ... }` — no Google Fonts, system fonts only.

**Font Sizes (custom classes)**
| Usage | Size |
|---|---|
| Sidebar nav links | `0.9rem` |
| Sidebar user name | `0.92rem` |
| Sidebar user email | `0.7rem` |
| Role pill / badges | `0.72rem` |
| Profile menu items | `0.89rem` |
| Profile name | `0.92rem` |
| Profile email | `0.77rem` |
| Brand text | `text-sm` (Tailwind, ~0.875rem) |
| Brand subtext | `text-xs` (Tailwind, ~0.75rem) |

---

## Color Palette

### CSS Variables (defined in `:root`)
```css
--maroon-soft:    #7a2a2a;   /* Primary — navbar, buttons */
--maroon-medium:  #5c1a1a;   /* Secondary — sidebar top, navbar gradient end */
--maroon-dark:    #3a0c0c;   /* Accent — sidebar bottom, role pill text */
--gold-primary:   #d4af37;   /* Highlights, active indicators, hover states */
--gold-secondary: #c9a227;   /* Gradient pair for gold elements */
--bg-warm:        #faf8f5;   /* Page background */
--border-soft:    #e5e0db;   /* Card/dropdown borders */
--text-primary:   #2c2420;   /* Main body text */
--text-secondary: #6b5e57;   /* Subtext, labels */
--text-muted:     #8b7e76;   /* Placeholder, disabled text */
```

### Color Usage Map
| Element | Color |
|---|---|
| Navbar background | `linear-gradient(135deg, #7a2a2a, #5c1a1a)` |
| Navbar border bottom | `rgba(212, 175, 55, 0.3)` |
| Navbar box shadow | `rgba(122, 42, 42, 0.25)` |
| Sidebar background | `linear-gradient(180deg, #5c1a1a, #3a0c0c)` |
| Sidebar border right | `rgba(212, 175, 55, 0.2)` |
| Active nav link bg | `linear-gradient(90deg, rgba(122,42,42,0.4), rgba(212,175,55,0.1))` |
| Active nav link border | `rgba(212, 175, 55, 0.3)` |
| Active indicator bar | `linear-gradient(180deg, #d4af37, #c9a227)` |
| Role pill (gold badge) | `linear-gradient(135deg, #d4af37, #c9a227)` |
| Role pill text | `#3a0c0c` (maroon-dark) |
| Avatar gradient | `linear-gradient(135deg, #7a2a2a, #d4af37)` |
| Page background | `#faf8f5` |
| Page bg radial overlays | `rgba(212,175,55,0.05)` + `rgba(122,42,42,0.05)` |
| Dropdown background | `rgba(255,255,255,0.98)` |
| Dropdown border | `#e5e0db` |
| Hover text color | `#d4af37` (gold) |
| Icon hover color | `#d4af37` (gold) |
| Scrollbar thumb | `rgba(212, 175, 55, 0.3)` |

---

## Layout

### Navbar
- Height: `4rem` (`h-16`)
- Fixed top, full width, `z-index: 40`
- Flex row, `justify-between`, `px-6`

### Sidebar
- Width expanded: `16rem` (`w-64`)
- Width collapsed: `5.5rem`
- Fixed left, top `4rem`, height `calc(100vh - 4rem)`
- `z-index: 30`

### Main Content
- `margin-left: 16rem` (expanded) / `5.5rem` (collapsed)
- `padding-top: 4rem` (navbar height)
- Transitions on margin: `0.3s ease`

### Sidebar Collapse
- Toggled via `body.sidebar-collapsed` class
- State persisted in `localStorage` key:
  - Counselor: `ogcSidebarCollapsed`
  - Student: `ogcStudentSidebarCollapsed`

---

## Component Classes

### `.ogc-navbar`
Maroon gradient top bar with gold border and blur.

### `.ogc-nav-icon`
Circular icon button — `2.6rem`, semi-transparent white bg, gold hover.

### `.ogc-brand-badge`
Logo container — `2.6rem`, rounded `0.9rem`, semi-transparent.

### `.ogc-sidebar`
Deep maroon gradient sidebar with ambient gold glow overlay via `::before`.

### `.sidebar-link` / `.logout-link`
Nav items — `0.8rem 0.9rem` padding, `0.75rem` border-radius, slide-right on hover (`translateX(4px)`).

### `.sidebar-link.active`
Warm maroon + gold gradient bg, gold left indicator bar (`0.25rem` wide, `1.5rem` tall).

### `.sidebar-user-avatar`
`2.7rem` square, `1rem` border-radius, maroon-to-gold gradient.

### `.sidebar-role-pill`
Gold gradient badge, `999px` border-radius, dark maroon text.

### `.ogc-profile-menu` / `.ogc-nav-dropdown-menu`
White frosted glass dropdown — `rgba(255,255,255,0.98)`, `blur(18px)`, `0.75rem` border-radius.

### `.ogc-profile-role`
Gold gradient inline badge inside dropdown header.

---

## Transitions

All sidebar/layout elements share this transition set:
```css
transition:
  width 0.3s ease,
  margin 0.3s ease,
  padding 0.3s ease,
  gap 0.3s ease,
  opacity 0.2s ease,
  transform 0.3s ease,
  background 0.25s ease,
  box-shadow 0.25s ease;
```

Icon hover: `transform: scale(1.1)`
Nav icon hover: `transform: translateY(-1px)`
Sidebar link hover: `transform: translateX(4px)`

---

## Bootstrap Overrides

### Tabs (`.nav-tabs`)
```css
.nav-tabs .nav-link          → color: #6b5e57, border-bottom: 3px solid transparent
.nav-tabs .nav-link.active   → color: #7a2a2a, border-bottom: 3px solid #7a2a2a
.nav-tabs .nav-link:hover    → border-bottom: 3px solid #d4af37
```

---

## Responsive Breakpoints

| Breakpoint | Sidebar Width | Content Margin |
|---|---|---|
| `> 1024px` | `16rem` | `16rem` |
| `≤ 1024px` | `16rem` | `16rem` |
| `≤ 768px` | `15rem` | `15rem` |
| Collapsed (any) | `5.5rem` / `5rem` | same |

---

## Icons
Font Awesome 6.4.0 free. Common icons used:
- `fa-bars` — sidebar toggle
- `fa-bell` — notifications
- `fa-user-circle` — profile
- `fa-sign-out-alt` — logout
- `fa-tachometer-alt` — dashboard
- `fa-calendar`, `fa-calendar-alt`, `fa-calendar-check` — calendar/appointments
- `fa-user-tie` — counselor avatar
- `fa-user-graduate` — student avatar
- `fa-graduation-cap` — student role pill
- `fa-stethoscope` — counselor role pill
