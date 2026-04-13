# How Google Calendar Integration Works

## Simple Overview

Your appointment booking system is connected to Google Calendar. When a student books an appointment, it automatically creates an event on the counselor's Google Calendar.

---

## Step-by-Step Explanation

### Step 1: Google Cloud Setup (One-time)
You created a Google Cloud project and got OAuth credentials. This is like getting permission to access Google Calendar on behalf of users.

**What you did:**
- Created a project in Google Cloud Console
- Enabled Google Calendar API
- Created OAuth credentials (Client ID & Secret)
- Downloaded the credentials JSON file

**Why:** This tells Google "this app is allowed to create calendar events"

---

### Step 2: Store Credentials in Your App
You saved the OAuth credentials file in your Laravel app:
```
storage/app/google-calendar/oauth-credentials.json
```

**Why:** Your app needs these credentials to talk to Google Calendar

---

### Step 3: Generate OAuth Token for Each Counselor
You ran this command:
```bash
php artisan google-calendar:oauth-token --email=jaica.dionaldo15@gmail.com
```

**What happened:**
1. The app opened a Google login page
2. You logged in with `jaica.dionaldo15@gmail.com`
3. You clicked "Allow" to give the app permission
4. Google gave you an authorization code
5. You pasted the code back into the terminal
6. The app saved a token file:
   ```
   storage/app/google-calendar/tokens/8.json
   ```

**Why:** This token proves that `jaica.dionaldo15@gmail.com` gave permission to create events on their calendar

---

## Why It Connected to MSU-IIT Email, Not Your Other Gmail

**The Answer:** You chose to connect it to `jaica.dionaldo15@gmail.com` instead of your MSU-IIT email.

**What happened:**
1. Initially, the seeder had `jaica.dionaldo@g.msuiit.edu.ph` (MSU-IIT email)
2. You changed it to `jaica.dionaldo15@gmail.com` (personal Gmail)
3. You generated the token for the Gmail account
4. Now all appointments sync to your Gmail calendar, not MSU-IIT

**If you want to use MSU-IIT email instead:**
```bash
php artisan google-calendar:oauth-token --email=jaica.dionaldo@g.msuiit.edu.ph --force
```

Then update the counselor:
```php
$counselor = Counselor::find(7);
$counselor->update(['google_calendar_id' => 'primary']);
```

**Key Point:** The system uses whatever email you authorize. You control which calendar gets the events.

---

### Step 4: Link Counselor to Google Calendar
You updated the counselor in the database:
```php
$counselor->update(['google_calendar_id' => 'primary']);
```

**What this means:**
- `google_calendar_id` = which Google Calendar to use
- `'primary'` = the main calendar of the logged-in user
- The system now knows: "When booking with this counselor, create events on their Google Calendar"

**Why:** The system needs to know which calendar belongs to which counselor

---

### Step 5: Student Books Appointment
When a student books an appointment:

1. **Student fills form:**
   - Selects counselor (e.g., Caryl Jan C. Encabo)
   - Picks date and time
   - Enters concern

2. **App saves to database:**
   - Creates appointment record in `appointments` table

3. **App creates Google Calendar event:**
   - Reads the counselor's `google_calendar_id` (e.g., `'primary'`)
   - Reads the token file (e.g., `tokens/8.json`)
   - Uses the token to authenticate with Google
   - Creates an event on the counselor's calendar
   - Saves the event ID in the appointment record

4. **Event appears on Google Calendar:**
   - Student name, date, time, and concern appear on counselor's calendar

---

## The Files Involved

### 1. OAuth Credentials (Setup once)
```
storage/app/google-calendar/oauth-credentials.json
```
- Contains: Client ID, Client Secret, Redirect URI
- Purpose: Tells Google who your app is

### 2. OAuth Token (Per counselor)
```
storage/app/google-calendar/tokens/8.json
```
- Contains: Access token, Refresh token, Expiration time
- Purpose: Proves the counselor gave permission
- Stored separately for each counselor (by user_id)

### 3. Counselor Database Record
```
counselors table:
- id: 7
- user_id: 8
- google_calendar_id: 'primary'
```
- Purpose: Links counselor to their Google Calendar

### 4. Appointment Record
```
appointments table:
- id: 1
- student_id: 1
- counselor_id: 7
- google_calendar_event_id: 'abc123xyz'
```
- Purpose: Stores the event ID so we can update/delete it later

---

## How It Works in Code

### When Booking:
```
Student clicks "Book Now"
    ↓
App validates form
    ↓
App gets counselor's google_calendar_id ('primary')
    ↓
App reads token file (tokens/8.json)
    ↓
App calls Google Calendar API with token
    ↓
Google creates event on counselor's calendar
    ↓
Event appears on Google Calendar ✓
```

### The Key Files:
- `app/Services/GoogleCalendarService.php` - Handles all Google Calendar operations
- `app/Http/Controllers/AppointmentController.php` - Handles booking logic
- `app/Console/Commands/GenerateGoogleCalendarToken.php` - Generates tokens

---

## Common Issues & Fixes

### "No available dates"
- **Cause:** Token file not found or calendar ID wrong
- **Fix:** Make sure token file exists and counselor has correct `google_calendar_id`

### "Not Found" error
- **Cause:** Calendar ID doesn't exist
- **Fix:** Use `'primary'` instead of email address

### Token expired
- **Cause:** Token is old and no longer valid
- **Fix:** Run `php artisan google-calendar:oauth-token --email=... --force`

---

## Testing Commands (Using Tinker)

### Check if Token Exists
```php
php artisan tinker
$tokenPath = storage_path('app/google-calendar/tokens/8.json');
file_exists($tokenPath);
```
**Expected:** `true`

---

### Check Token Contents
```php
$token = json_decode(file_get_contents($tokenPath), true);
$token;
```
**Expected:** Array with `access_token`, `refresh_token`, `expires_in`, etc.

---

### Check Counselor Details
```php
$counselor = Counselor::find(7);
$counselor;
```
**Expected:** Shows counselor info including `google_calendar_id: 'primary'`

---

### Check Counselor Availability
```php
$counselor->getAvailability();
```
**Expected:** 
```
["monday" => ["08:00-12:00","13:00-17:00"], "tuesday" => [...], ...]
```

---

### Test Google Calendar Connection
```php
$calendarService = new \App\Services\GoogleCalendarService();
$date = \Carbon\Carbon::today()->addDay();
$busyIntervals = $calendarService->getBusyIntervalsForDate('primary', $date);
$busyIntervals;
```
**Expected:** Empty array `[]` (means no events, all slots available)

---

### Update Counselor's Calendar ID
```php
$counselor = Counselor::find(7);
$counselor->update(['google_calendar_id' => 'primary']);
```
**Expected:** `true`

---

### Exit Tinker
```php
exit
```

---

## Full Testing Workflow

```php
php artisan tinker

// 1. Check token exists
$tokenPath = storage_path('app/google-calendar/tokens/8.json');
file_exists($tokenPath);  // Should be true

// 2. Check token is valid
$token = json_decode(file_get_contents($tokenPath), true);
$token['access_token'];  // Should show long token string

// 3. Check counselor
$counselor = Counselor::find(7);
$counselor->google_calendar_id;  // Should be 'primary'

// 4. Check availability
$counselor->getAvailability();  // Should show working hours

// 5. Test calendar connection
$calendarService = new \App\Services\GoogleCalendarService();
$date = \Carbon\Carbon::today()->addDay();
$busyIntervals = $calendarService->getBusyIntervalsForDate('primary', $date);
$busyIntervals;  // Should be []

// 6. Exit
exit
```

If all these commands work, your Google Calendar integration is ready!
