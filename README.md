# Job Portal System (PHP & MySQL)

A modular, native PHP and MySQL Job Portal Web Application.

## System Modules
- **Authentication & Security:** Role-based access control (Admin, Employer, Candidate) with CSRF and secure sessions.
- **Job Management:** Employer job postings, category filters, and application tracking.
- **Candidate Portal:** Resume uploads, job search, and real-time status dashboard.
- **Admin Panel:** Global platform statistics and user moderation.

## Directory Structure
- `config/` - Database and application configuration
- `controllers/` - Application controllers and business logic
- `models/` - Database models and queries
- `views/` - UI layouts and page templates
- `public/` - Public entry point (`index.php`), CSS, JS, and uploads
- `helpers/` - Utility functions (security, session, pagination)