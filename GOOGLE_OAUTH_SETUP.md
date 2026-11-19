# Google OAuth Setup Guide

## Step 1: Create Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Go to "APIs & Services" → "Credentials"
4. Click "Create Credentials" → "OAuth 2.0 Client ID"
5. If prompted, configure the OAuth consent screen first:
   - Choose "External" (unless you have a Google Workspace)
   - Fill in required fields (App name, User support email, Developer contact)
   - Add scopes: `email`, `profile`, `openid`
   - Add test users (your email) if in testing mode
6. Create OAuth 2.0 Client ID:
   - Application type: **Web application**
   - Name: "AEP App Registration" (or any name)
   - **Authorized redirect URIs**: Add the exact callback URL used by this app. For example, if `APP_URL=http://127.0.0.1:8000`, add:
     ```
     http://127.0.0.1:8000/auth/google/callback
     ```
     If you use `localhost`, XAMPP, or a different port, use that exact base URL instead. For production, use `https://yourdomain.com/auth/google/callback`.
7. Click "Create"
8. Copy the **Client ID** and **Client Secret**

## Step 2: Add Credentials to .env File

Open your `.env` file and add:

```env
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
APP_URL=http://127.0.0.1:8000
```

The callback is `APP_URL` plus `/auth/google/callback`. If it differs from that URL, set it explicitly:

```env
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

**Important:** 
- Replace `your-client-id-here` and `your-client-secret-here` with the actual values from Google Cloud Console
- Make sure there are no spaces or quotes around the values
- `APP_URL` (or `GOOGLE_REDIRECT_URI` when set) must match the authorized redirect URI exactly

## Step 3: Clear Config Cache

Run this command in your terminal:

```bash
php artisan config:clear
```

## Step 4: Test

1. Try registering with Google OAuth again
2. You should be redirected to Google's login page
3. After logging in, you'll be redirected back to the application

## Troubleshooting

### Error 400: invalid_request
- **Cause**: Redirect URI mismatch
- **Fix**: Make sure the redirect URI in Google Cloud Console **exactly matches**:
  - The callback URL formed from your configured `APP_URL` or `GOOGLE_REDIRECT_URI`
- **Note**: No trailing slashes, must be HTTP (not HTTPS) for local development

### Error: redirect_uri_mismatch
- **Cause**: The redirect URI in your request doesn't match what's authorized
- **Fix**: Check that `APP_URL` or `GOOGLE_REDIRECT_URI` in `.env` matches the authorized redirect URI in Google Cloud Console

### Still getting "Google OAuth is not configured"
- Make sure you added the credentials to `.env` (not `.env.example`)
- Run `php artisan config:clear` after adding credentials
- Check that there are no typos in the variable names: `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET`

## Step 5: Production Setup

For production:
1. Add your production URL to authorized redirect URIs in Google Cloud Console
2. Update `APP_URL` in production `.env` to your production domain
3. If `GOOGLE_REDIRECT_URI` is set, update it to the same production callback URL
4. Make sure to use HTTPS in production
