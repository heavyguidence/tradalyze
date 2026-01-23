# Tradalyze

A comprehensive trading journal and analytics platform for tracking, analyzing, and improving your trading performance.

![Tradalyze Dashboard](screenshots/Tradalyze%20Dashboard.png)

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## Screenshots

Explore the application interface and features in our [Screenshots Gallery](screenshots/).

**Quick Preview:**
- [Dashboard](screenshots/Tradalyze%20Dashboard.png) - Performance overview with charts and statistics
- [Trade History](screenshots/Tradalyze%20Trades%20Summary.png) - Comprehensive trade listing with filters
- [Trade Detail](screenshots/Trade%20Detail.png) - Individual trade information
- [Add Trade](screenshots/Add%20Trades%20manual%20entry.png) - Manual trade entry interface
- [Auto Import](screenshots/Add%20Trades%20auto%20import.png) - Interactive Brokers auto-import
- [CSV Import](screenshots/Add%20Trades%20Import%20CSV.png) - Bulk import from CSV files
- [Trading Diary](screenshots/Tradalyze%20Diary%20Entry.png) - Journal your trading journey
- [Login Page](screenshots/Tradalyze%20Login%20Page.png) - Clean authentication interface

## Features

- 📊 **Trade Management** - Track stocks, options, and futures positions
- 📈 **FIFO Position Tracking** - Automatic first-in-first-out position calculation
- 📅 **Trading Diary** - Document your trading decisions and lessons learned
- 🔄 **Auto Import** - Import trades directly from Interactive Brokers using Flex API
- 📉 **Performance Analytics** - Comprehensive dashboard with P&L charts and statistics
- 🏷️ **Trade Tags** - Organize trades by setup type and strategy
- 🔍 **Advanced Filtering** - Filter trades by symbol, date, P&L, tags, and more
- 📱 **Responsive Design** - Works seamlessly on desktop and mobile devices

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 20.x or higher
- MySQL 8.0+ or SQLite
- Git

## Installation


### Docker Deployment (Recommended)

The application includes Docker configuration for easy deployment.

**Prerequisites:**
- Docker Engine 20.x or higher
- Docker Compose v2.x or higher

**Easiest Way**

Pull Tradalyze's official image from docker hub and follow the instructions provided in there.

[Tradalyze Docker Hub Image ](https://hub.docker.com/r/yeahwethenorth/tradalyze)

**Quick Start if you want to build your own image or want to play around with Tradalyze**

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/tradalyze.git
   cd tradalyze
   ```

2. **Create environment file:**
   ```bash
   cp .env.example .env
   ```
   
   **Generate APP_KEY:**
   
   Choose one of the following methods:
   
   **Method 1: Using OpenSSL (Recommended for Docker users)**
   ```bash
   # Linux/Mac:
   echo "base64:$(openssl rand -base64 32)"
   
   # Windows PowerShell:
   "base64:$([Convert]::ToBase64String((1..32 | ForEach-Object { Get-Random -Minimum 0 -Maximum 256 })))"
   ```
   
   **Method 2: Using PHP Artisan (If PHP is installed locally)**
   ```bash
   php artisan key:generate --show
   ```
   
   **Method 3: Online Generator**
   - Visit: https://generate-random.org/laravel-key-generator
   - Copy the generated key
   
   Copy the generated key and update `.env`:
   ```env
   APP_KEY=base64:YOUR_GENERATED_KEY_HERE
   ```
   
   **Create SQLite database:**
   ```bash
   touch database/database.sqlite
   # On Windows: New-Item database/database.sqlite
   ```

3. **Build and start containers:**
   ```bash
   docker-compose up -d --build
   ```

4. **Run migrations (first time only):**
   ```bash
   docker-compose exec app php artisan migrate --force
   docker-compose exec app php artisan db:seed --class=TradeTagSeeder --force
   docker-compose exec app php artisan storage:link
   ```

5. **Access the application:**
   Open `http://localhost:8080` in your browser

**Using MySQL instead of SQLite:**

Uncomment the MySQL service in `docker-compose.yml` and update your `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tradalyze
DB_USERNAME=tradalyze_user
DB_PASSWORD=tradalyze_password
```

**Production Environment Variables:**

Edit `.env` before building:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_LEVEL=error
```


## Usage

### Register an Account

1. Navigate to `/register`
2. Create your account with email and password
3. Start tracking your trades!

### Import Trades

**From CSV:**
1. Export your trade history from your broker
2. Go to **Add Trade → Import CSV**
3. Select Interactive Broker format
4. Upload the CSV file

**Auto Import:**
1. Configure your IB credentials in Settings
2. Go to **Add Trade → Auto Import Trades**
3. Click **Import Trades**
4. Wait for the import to complete

To use the Auto Import feature:

1. Log in to your Interactive Brokers account
2. Navigate to **Account Management → Reports → Flex Queries**
3. Create a new Flex Query with trade execution data
      3. Create a new Flex Query with the following configuration:
         - **Name your query:** Any descriptive name
         - **Sections list:** Select "Trades"
         - **Fields:** Select all fields under Trades and click Save
         - **Delivery Configuration:**
           - Models: Optional
           - Format: CSV
           - Include header and trailer records?: No
           - Include column headers?: Yes
           - Display single column header row?: No
           - Include section code and line descriptor?: No
           - Period: YTD (or as needed)
         - **General Configuration:**
           - Date Format: yyyy-MM-DD
           - Time Format: HH:mm:ss
           - Date/Time Separator: (single space)
           - Profit and Loss: Default
           - Everything else set to "No"
         - Click **Continue** > **Save Changes**
         - Reopen the created query to view the **Query ID**
      4. Note your **Query ID**
5. Generate a **Flex Token** from security settings
6. In Tradalyze, go to **Add Trade → Auto Import Trades** tab
7. Enter your Flex Token and Query ID > Save Credentials
8. Click **Import Trades**

### View Analytics

The Dashboard provides:
- Total trades and win rate
- Net profit/loss statistics
- Daily P&L bar chart for current month
- Calendar heatmap of trading activity
- Recent trades overview

## Tech Stack

- **Backend:** Laravel 11.x
- **Frontend:** Blade Templates, TailwindCSS
- **Database:** SQLite / MySQL
- **Charts:** Chart.js, FullCalendar
- **Build Tool:** Vite

## Security

- All routes require authentication except login/register
- CSRF protection on all forms
- SQL injection protection via Eloquent ORM
- XSS protection via Blade templating
- Passwords hashed with bcrypt

## Troubleshooting

**Port already in use:**
```bash
php artisan serve --port=8001
```

**Permission errors:**
```bash
chmod -R 775 storage bootstrap/cache
```

**NPM build errors:**
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

**Database errors:**
```bash
php artisan migrate:fresh --seed
```

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI components from [TailwindCSS](https://tailwindcss.com)
- Charts powered by [Chart.js](https://www.chartjs.org)
- Calendar by [FullCalendar](https://fullcalendar.io)

---

## Contributing to Tradalyze

We welcome contributions from the developer community to help make Tradalyze the ultimate tool for tracking options, stocks, and futures. Whether you are a seasoned trader or a developer passionate about fintech, there are several ways to get involved:

**Broker Integrations**: Help the community connect with more platforms by building and testing API integrations.

**Backtesting & Strategy**: Develop engines to help users validate their trading strategies against historical data.

**Charting & Visualization**: Enhance our data dashboards with advanced, interactive financial charts.

**Core Infrastructure**: Optimize our database performance, refine the UI, or improve our self-hosting deployment workflows.

To get started, please check out our open issues or submit a Pull Request. We are excited to see how your expertise can help shape the future of the platform!

**Happy Trading! 📈**
