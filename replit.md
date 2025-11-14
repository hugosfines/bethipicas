# Overview

This is a Laravel-based horse racing betting application built with PHP. The system appears to manage horse racing events, tracks, and betting operations. It uses Laravel 12 with Jetstream for authentication and team management, Livewire for dynamic interfaces, and includes PDF generation capabilities for reporting.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Backend Framework
**Problem**: Need a robust PHP framework for web application development with modern features.

**Solution**: Laravel 12 framework with PHP 8.2+

**Rationale**: Laravel provides comprehensive tools for routing, authentication, database operations, and more. It's widely adopted with excellent documentation and community support.

**Key Components**:
- MVC architecture pattern
- Eloquent ORM for database operations
- Blade templating engine
- Artisan CLI for development tasks

## Authentication & Authorization
**Problem**: Secure user authentication and role-based access control.

**Solution**: Laravel Jetstream with Sanctum authentication and Spatie Permission package

**Components**:
- Laravel Jetstream: Team management and user authentication scaffolding
- Laravel Sanctum: API token authentication
- Spatie Laravel Permission: Role and permission management

**Rationale**: Jetstream provides battle-tested authentication flows, while Spatie Permissions offers flexible role-based access control. Sanctum handles API authentication for potential mobile or external integrations.

## Frontend Stack
**Problem**: Create interactive, responsive user interfaces.

**Solution**: Livewire with TailwindCSS and Alpine.js

**Components**:
- Livewire 3.0: Server-side rendering with reactive components
- TailwindCSS: Utility-first CSS framework with custom design system
- Alpine.js: Minimal JavaScript framework for UI interactivity
- Vite: Modern build tool for asset compilation

**Design System**: Custom color palette defined in tailwind.config.js with primary (gray), warning (orange), danger (red), and system (amber) color schemes.

## Database Architecture
**Problem**: Store and manage horse racing data, tracks, users, and betting information.

**Solution**: Relational database using Laravel's migration system

**Key Aspects**:
- Database-agnostic through Laravel's query builder
- Eloquent ORM for object-relational mapping
- Schema migrations for version control
- Seeders for initial data (tracks.json contains racing track information)

**Data Seeding**: Includes comprehensive track data with codes for various racing venues (Aqueduct, Churchill Downs, Del Mar, etc.).

## PDF Generation
**Problem**: Generate PDF reports for betting slips, race results, or financial reports.

**Solution**: DomPDF library via Laravel wrapper

**Package**: barryvdh/laravel-dompdf

**Rationale**: DomPDF converts HTML/CSS to PDF without external dependencies, making it ideal for generating printable documents from Blade templates.

## Data Export
**Problem**: Export data in spreadsheet formats for reporting.

**Solution**: Spatie Simple Excel package

**Use Cases**: Export race results, betting history, or financial data to Excel/CSV formats.

## Internationalization
**Problem**: Support multiple languages, particularly Spanish.

**Solution**: Laravel's built-in localization system

**Implementation**: Spanish translations provided in lang/es.json for common UI elements and validation messages.

## User Notifications
**Problem**: Provide user feedback for actions and events.

**Solution**: Multiple toast notification systems

**Packages**:
- usernotnull/tall-toasts: TALL stack-compatible toast notifications
- yoeunes/toastr: Additional toast notification option

**Integration**: Uses Flasher library for unified notification handling across different notification libraries.

## Testing Infrastructure
**Problem**: Ensure code quality and prevent regressions.

**Solution**: Pest PHP testing framework

**Components**:
- Pest 3.8: Modern testing framework with expressive syntax
- Laravel-specific Pest plugin for framework testing
- Parallel test execution support via ParaTest

**Rationale**: Pest provides a more developer-friendly API compared to PHPUnit while maintaining compatibility.

## Development Tools
**Problem**: Code quality, formatting, and debugging.

**Solution**: Suite of development packages

**Tools**:
- Laravel Pail: Real-time log tailing
- Laravel Pint: Code style fixer
- Laravel Sail: Docker development environment
- Mockery: Mocking framework for tests

## Asset Management
**Problem**: Efficiently compile and serve frontend assets.

**Solution**: Vite build system

**Configuration**:
- Hot module replacement for development
- Asset bundling and optimization for production
- Automatic cache busting via manifest.json

**Entry Points**: 
- resources/css/app.css (Tailwind styles)
- resources/js/app.js (Alpine.js and toast components)

## Helper Functions
**Problem**: Reusable utility functions across the application.

**Solution**: Custom helper files with global functions

**Files**:
- app/Helpers/helpers.php: General utility functions
- app/Helpers/hstatics.php: Static helper methods

**Loading**: Auto-loaded via composer.json files section.

# External Dependencies

## Third-Party Services
- **Preline UI**: Pre-built UI components (vendor/preline/)
- **jQuery**: JavaScript library (included via Flasher)
- **PrismJS**: Syntax highlighting for error pages (via Whoops)

## PHP Extensions Required
- ext-dom: XML/HTML manipulation
- ext-mbstring: Multibyte string handling
- ext-iconv: Character encoding conversion

## Composer Packages
- **guzzlehttp/guzzle**: HTTP client for external API calls
- **masterminds/html5**: HTML5 parsing for DomPDF
- **webmozart/assert**: Runtime assertions
- **symfony/polyfill-intl-idn**: Internationalized domain name support

## Development Dependencies
- **Collision**: Beautiful error reporting for console
- **Faker**: Test data generation
- **PHPStan/Psalm**: Static analysis (via dependencies)

## CDN/External Assets
- Flasher notification libraries (vendor/flasher/)
- Font libraries for PDF generation (dompdf/php-font-lib)
- SVG rendering library for PDFs (dompdf/php-svg-lib)