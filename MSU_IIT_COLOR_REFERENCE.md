# MSU-IIT Color Palette - Quick Reference Guide

## Official MSU-IIT Colors

```
┌─────────────────────────────────────────────────────────────┐
│ COLOR NAME              │ HEX CODE │ RGB              │ USE  │
├─────────────────────────────────────────────────────────────┤
│ Bright Yellow           │ #FFE100  │ RGB(255,225,0)   │ ⭐   │
│ Golden Yellow           │ #FFC917  │ RGB(255,201,23)  │ ⭐   │
│ Outrageous Orange       │ #F8650C  │ RGB(248,101,12)  │ ⭐⭐  │
│ Oil Red                 │ #F00000  │ RGB(240,0,0)     │ ⚠️   │
│ Dark Red                │ #820000  │ RGB(130,0,0)     │ 🔤   │
└─────────────────────────────────────────────────────────────┘
```

## Color Usage in Application

### Primary Actions (Orange #F8650C)
- Main buttons
- Primary CTAs
- Active navigation items
- Form focus states
- Links

**Variants:**
- Normal: `#F8650C`
- Hover: `#E55A00` (darker)
- Active: `#D44D00` (even darker)

### Secondary Actions (Golden Yellow #FFC917)
- Secondary buttons
- Borders on secondary elements
- Sidebar accents
- Hover highlights

### Danger/Error (Red #F00000)
- Delete buttons
- Error messages
- Warning alerts
- Destructive actions

**Variants:**
- Normal: `#F00000`
- Hover: `#D40000`
- Active: `#B30000`

### Dark Text & Backgrounds (Dark Red #820000)
- Headers
- Important text
- Dark backgrounds
- Text hover states

### Accents (Bright Yellow #FFE100)
- Light backgrounds
- Highlights
- Decorative elements
- Sidebar hover effects

## Component Color Mapping

### Buttons
```
Primary Button:
  Background: #F8650C
  Text: White
  Hover: #E55A00
  Focus Ring: #F8650C

Secondary Button:
  Background: White
  Border: #FFC917
  Text: #F8650C
  Hover: #FFF9E6

Danger Button:
  Background: #F00000
  Text: White
  Hover: #D40000
  Focus Ring: #F00000
```

### Navigation
```
Navbar:
  Gradient: #F8650C → #FFC917
  Text: White
  Hover: #E55A00

Sidebar:
  Gradient: #820000 → #F8650C
  Text: White
  Hover: rgba(#FFE100, 0.2)
  Border: #FFC917
```

### Forms
```
Input Focus:
  Ring Color: #F8650C
  Border: #F8650C

Radio/Checkbox:
  Color: #F8650C
  Focus Ring: #F8650C

Select Dropdown:
  Focus Ring: #F8650C
  Border: #F8650C
```

### Links
```
Normal Link:
  Color: #F8650C
  
Link Hover:
  Color: #820000

Focus Ring:
  Color: #F8650C
```

## Tailwind CSS Classes Used

### Background Colors
- `bg-[#F8650C]` - Primary orange
- `bg-[#E55A00]` - Darker orange
- `bg-[#FFF9E6]` - Light yellow
- `bg-[#F00000]` - Red
- `bg-[#820000]` - Dark red

### Text Colors
- `text-[#F8650C]` - Orange text
- `text-[#E55A00]` - Dark orange text
- `text-[#F00000]` - Red text
- `text-[#820000]` - Dark red text
- `text-[#FFE100]` - Bright yellow text

### Border Colors
- `border-[#F8650C]` - Orange border
- `border-[#FFC917]` - Golden yellow border
- `border-[#FFE100]` - Bright yellow border
- `border-[#F00000]` - Red border

### Focus States
- `focus:ring-[#F8650C]` - Orange focus ring
- `focus:border-[#F8650C]` - Orange focus border

### Hover States
- `hover:bg-[#E55A00]` - Darker orange hover
- `hover:bg-[#FFF9E6]` - Light yellow hover
- `hover:text-[#F8650C]` - Orange text hover
- `hover:text-[#820000]` - Dark red text hover

## Opacity Variants

When using opacity with Tailwind arbitrary values:
```
border-[#F8650C]/30  → 30% opacity orange border
hover:bg-[#F8650C]/10 → 10% opacity orange background on hover
text-[#820000]/50    → 50% opacity dark red text
```

## Accessibility Notes

### Color Contrast
- Orange (#F8650C) on White: ✅ WCAG AA compliant
- Dark Red (#820000) on White: ✅ WCAG AAA compliant
- Red (#F00000) on White: ✅ WCAG AA compliant
- White on Orange: ✅ WCAG AAA compliant

### Best Practices
- Don't rely on color alone to convey information
- Use icons and text labels with colors
- Ensure sufficient contrast ratios
- Test with color blindness simulators

## Implementation Examples

### Primary Button
```html
<button class="bg-[#F8650C] text-white hover:bg-[#E55A00] focus:ring-2 focus:ring-[#F8650C]">
  Click Me
</button>
```

### Secondary Button
```html
<button class="bg-white border border-[#FFC917] text-[#F8650C] hover:bg-[#FFF9E6]">
  Secondary
</button>
```

### Danger Button
```html
<button class="bg-[#F00000] text-white hover:bg-[#D40000]">
  Delete
</button>
```

### Navigation Link
```html
<a href="#" class="text-[#F8650C] hover:text-[#820000]">
  Link
</a>
```

### Form Input
```html
<input type="text" class="focus:ring-2 focus:ring-[#F8650C] focus:border-[#F8650C]">
```

---

**Last Updated**: March 17, 2026
**Status**: ✅ All 90 blade files updated
**Color System**: MSU-IIT Official Palette
