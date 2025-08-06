# School Management System

A comprehensive, production-ready Laravel 12 school management application with Bootstrap 5, SQL Server backend, and role-based access control.

## 🎓 Features

### Core Modules
- **Academic Management**: Academic years, terms, classes, sections, subjects
- **User Management**: Admin, Teachers, Parents, Students with role-based access
- **Student Management**: Enrollment, profiles, attendance tracking
- **Teacher Management**: Timetables, grade entry, attendance marking
- **Parent Portal**: View children's progress, attendance, grades, fees
- **Student Portal**: Personal dashboard, timetable, grades, attendance
- **Attendance System**: Real-time attendance tracking with status (Present/Absent/Late)
- **Grade Management**: Comprehensive grading system with report cards
- **Fee Management**: Fee structure, payment tracking, financial reports
- **Communication**: Announcements, notifications, messaging system
- **Gallery**: Photo management for school events and activities

### Technical Features
- **Responsive Design**: Mobile-first approach with Bootstrap 5
- **Modern UI/UX**: Smooth animations, AOS (Animate On Scroll), interactive components
- **Role-Based Access**: Granular permissions for different user types
- **Real-time Notifications**: Email, SMS, and push notifications
- **PDF Generation**: Report cards and certificates
- **API Ready**: RESTful API endpoints for mobile apps
- **Search & Filter**: Advanced search functionality across all modules
- **Data Export**: CSV/Excel export capabilities
- **Audit Trail**: Activity logging for security and compliance

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- SQL Server 2019 or higher
- Node.js & NPM (for asset compilation)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd school-management
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your SQL Server credentials:
   ```env
   DB_CONNECTION=sqlsrv
   DB_HOST=127.0.0.1
   DB_PORT=1433
   DB_DATABASE=school_management
   DB_USERNAME=sa
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Compile assets**
   ```bash
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👥 Default Users

After running the seeders, you'll have these default users:

### Super Admin
- **Username**: superadmin
- **Email**: admin@school.com
- **Password**: password

### Admin
- **Username**: admin
- **Email**: admin2@school.com
- **Password**: password

### Teachers
- **Username**: teacher1, teacher2, teacher3
- **Email**: teacher1@school.com, teacher2@school.com, teacher3@school.com
- **Password**: password

### Parents
- **Username**: parent1, parent2
- **Email**: parent1@school.com, parent2@school.com
- **Password**: password

### Students
- **Username**: student1, student2, student3
- **Email**: student1@school.com, student2@school.com, student3@school.com
- **Password**: password

## 🏗️ Architecture

### Database Schema
The application uses a normalized database schema with the following key tables:

- **academic_years**: Academic year management
- **terms**: Term periods within academic years
- **classes & sections**: Class and section organization
- **subjects**: Subject catalog
- **users**: Central user management with role-based access
- **enrollments**: Student enrollment records
- **timetables**: Class schedules
- **attendance**: Daily attendance tracking
- **grades**: Academic performance records
- **fees & payments**: Financial management
- **announcements**: Communication system

### MVC Structure
- **Models**: Eloquent ORM with relationships and business logic
- **Controllers**: RESTful controllers with validation and authorization
- **Views**: Blade templates with Bootstrap 5 components
- **Services**: Business logic layer for complex operations

### Security Features
- **Authentication**: Laravel Breeze with role-based access
- **Authorization**: Spatie Laravel Permission package
- **CSRF Protection**: Built-in Laravel CSRF protection
- **Input Validation**: Comprehensive form validation
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries

## 🎨 Frontend

### Technologies
- **Bootstrap 5**: Responsive CSS framework
- **Bootstrap Icons**: Icon library
- **AOS (Animate On Scroll)**: Smooth scroll animations
- **Custom CSS**: Modern styling with CSS variables
- **Vanilla JavaScript**: Interactive features and utilities

### Responsive Design
- **Mobile-first approach**: Optimized for all screen sizes
- **Flexible grid system**: Bootstrap's responsive grid
- **Touch-friendly**: Optimized for mobile devices
- **Progressive enhancement**: Works without JavaScript

### UI Components
- **Dashboard cards**: Statistics and quick actions
- **Data tables**: Sortable, searchable, and exportable
- **Forms**: Validation and user-friendly inputs
- **Modals**: Interactive dialogs
- **Notifications**: Toast messages and alerts
- **Charts**: Data visualization (Chart.js integration ready)

## 🔧 Configuration

### Environment Variables
Key configuration options in `.env`:

```env
APP_NAME="School Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=school_management
DB_USERNAME=sa
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@school.com"
MAIL_FROM_NAME="${APP_NAME}"

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
```

### File Permissions
Ensure proper file permissions:
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📊 Features by User Role

### Super Admin
- Full system access
- User and role management
- System configuration
- Audit logs

### Admin
- Academic year and term management
- Class, section, and subject management
- User management (teachers, students, parents)
- Enrollment management
- Timetable creation
- Attendance monitoring
- Grade management
- Fee structure management
- Report generation
- Announcement management
- Gallery management

### Teacher
- View personal timetable
- Mark attendance for assigned classes
- Enter and manage grades
- View student lists
- Generate class reports

### Parent
- View children's information
- Monitor attendance records
- View academic progress
- Access fee information
- Make fee payments
- View timetables

### Student
- Personal dashboard
- View attendance records
- Access grades and report cards
- View class timetable
- Update profile information

## 🚀 Deployment

### Production Setup

1. **Server Requirements**
   - PHP 8.2+
   - SQL Server 2019+
   - Web server (Apache/Nginx)
   - SSL certificate

2. **Deployment Steps**
   ```bash
   # Clone to production server
   git clone <repository-url> /var/www/school-management
   cd /var/www/school-management

   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm install && npm run build

   # Environment setup
   cp .env.example .env
   # Edit .env with production settings

   # Database setup
   php artisan migrate --force
   php artisan db:seed --force

   # Cache optimization
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

   # Set permissions
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 755 storage bootstrap/cache
   ```

3. **Web Server Configuration**
   ```apache
   # Apache (.htaccess included)
   <VirtualHost *:80>
       ServerName school.example.com
       DocumentRoot /var/www/school-management/public
       
       <Directory /var/www/school-management/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

4. **Cron Jobs**
   ```bash
   # Add to crontab
   * * * * * cd /var/www/school-management && php artisan schedule:run >> /dev/null 2>&1
   ```

## 🔒 Security Considerations

- **HTTPS**: Always use HTTPS in production
- **Strong Passwords**: Enforce strong password policies
- **Regular Updates**: Keep Laravel and dependencies updated
- **Backup Strategy**: Regular database and file backups
- **Access Logs**: Monitor system access and activities
- **Input Validation**: Validate all user inputs
- **SQL Injection**: Use Eloquent ORM for database queries

## 📈 Performance Optimization

- **Caching**: Use Laravel's caching system
- **Database Indexing**: Optimize database queries
- **Asset Optimization**: Minify CSS/JS files
- **Image Optimization**: Compress uploaded images
- **CDN**: Use CDN for static assets
- **Database Connection Pooling**: Optimize database connections

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify SQL Server is running
   - Check connection credentials in `.env`
   - Ensure SQL Server drivers are installed

2. **Permission Errors**
   - Set proper file permissions
   - Check web server user permissions

3. **Asset Loading Issues**
   - Run `npm run dev` or `npm run build`
   - Check asset paths in views

4. **Migration Errors**
   - Ensure database exists
   - Check SQL Server compatibility
   - Verify migration files are correct

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the troubleshooting section

## 🔄 Updates

To update the application:
```bash
git pull origin main
composer install
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Built with ❤️ using Laravel 12, Bootstrap 5, and SQL Server**
