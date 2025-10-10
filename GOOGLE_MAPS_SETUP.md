# Google Maps API Setup Instructions

To use the Bristol Trees application, you need to configure a Google Maps API key.

## Steps to Get Your API Key

1. **Go to Google Cloud Console**
   - Visit [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select an existing one

2. **Enable Required APIs**
   - Navigate to "APIs & Services" > "Library"
   - Search for and enable the following APIs:
     - **Maps JavaScript API**
     - **Geocoding API** (optional, for future features)

3. **Create API Credentials**
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "API Key"
   - Copy the generated API key

4. **Restrict Your API Key (Recommended)**
   - Click on the API key you just created
   - Under "Application restrictions", select "HTTP referrers"
   - Add your domain(s), e.g., `localhost:8000/*` for development
   - Under "API restrictions", select "Restrict key"
   - Select only the APIs you enabled (Maps JavaScript API)
   - Save the changes

## Configure the Application

1. **Update the `.env` file**
   ```bash
   GOOGLE_MAPS_API_KEY=your_actual_api_key_here
   ```

2. **Clear the configuration cache** (if needed)
   ```bash
   php artisan config:clear
   ```

## For Development/Testing

If you want to test the application without a real API key:
- The maps will show an error overlay but the application will still function
- Tree markers won't display on the map without a valid API key
- You can still access all other features (authentication, reviews, ratings, etc.)

## Cost Considerations

- Google Maps Platform offers a free tier with $200 monthly credit
- For most small to medium projects, this is sufficient
- Monitor your usage in the Google Cloud Console
- Set up billing alerts to avoid unexpected charges

## Troubleshooting

- **"This page can't load Google Maps correctly"**: Check that your API key is valid
- **"RefererNotAllowedMapError"**: Verify your HTTP referrer restrictions
- **Maps not loading**: Ensure the Maps JavaScript API is enabled

For more information, visit the [Google Maps Platform Documentation](https://developers.google.com/maps/documentation).
