# BristolTrees

A Laravel web application that displays Bristol's trees on an interactive Google Maps interface using data from Bristol City Council's API.

## Features

- **Interactive Map**: View all Bristol trees on a Google Maps interface
- **Tree Information**: Click on any tree marker to view detailed information
- **User Authentication**: Register and login to access additional features
- **Rating System**: Rate trees from 1-5 stars
- **Review System**: Leave reviews and comments about trees
- **Image Upload**: Upload photos of trees (requires admin approval)
- **Admin Dashboard**: Admin panel for approving/rejecting user-uploaded images
- **Data Sync**: Sync tree data from Bristol City Council's API

## Installation

1. Clone the repository:
```bash
git clone https://github.com/ChickenDeAlecay/BristolTrees.git
cd BristolTrees
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your `.env` file:
   - Set your Google Maps API key (replace `YOUR_GOOGLE_MAPS_API_KEY` in the views)
   - Database is pre-configured to use SQLite

5. Create database and run migrations:
```bash
touch database/database.sqlite
php artisan migrate
```

6. Create storage link:
```bash
php artisan storage:link
```

7. Build assets:
```bash
npm run build
```

8. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Google Maps API Setup

1. Get a Google Maps API key from [Google Cloud Console](https://console.cloud.google.com/)
2. Enable the following APIs:
   - Maps JavaScript API
   - Geocoding API
3. Replace `YOUR_GOOGLE_MAPS_API_KEY` in:
   - `resources/views/trees/index.blade.php`
   - `resources/views/trees/show.blade.php`

## Usage

### Syncing Tree Data

To sync tree data from Bristol City Council's API:
1. Login as an admin user
2. Click the "Sync Trees" button on the main map page

### Creating an Admin User

Run the following in `php artisan tinker`:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = bcrypt('password');
$user->is_admin = true;
$user->save();
```

### User Features

- **View Trees**: Browse trees on the interactive map
- **Rate Trees**: Login to rate trees from 1-5 stars
- **Review Trees**: Leave detailed reviews about trees
- **Upload Images**: Upload photos of trees (pending admin approval)

### Admin Features

- **Approve Images**: Review and approve user-uploaded tree images
- **Reject Images**: Reject inappropriate or low-quality images
- **Sync Data**: Update tree data from Bristol City Council's API

## Data Source

Tree data is sourced from Bristol City Council's public API:
https://maps2.bristol.gov.uk/server2/rest/services/ext/ll_environment_and_planning/MapServer/32

## Technology Stack

- **Framework**: Laravel 12
- **Authentication**: Laravel Breeze
- **Database**: SQLite (configurable)
- **Frontend**: Blade Templates, Tailwind CSS
- **Maps**: Google Maps JavaScript API
- **Image Storage**: Laravel Storage (public disk)

## License

This project is open-source software.
