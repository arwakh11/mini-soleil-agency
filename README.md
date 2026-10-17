# Mini Soleil Agency

A full stack web application for a photo and video production agency, built with **Symfony 7**. Clients request a quote online; the team manages clients, shoots, models and crew from a secure back office.

Built by **Arwa Alzahrani** as the final project of the *Titre Professionnel Développeur Web et Web Mobile* (DWWM) at GRETA – CFA Côte d'Azur, Nice.

## Features

**Public site**
- Presentation of the agency's services and creative work
- Online quote request form: shoot type (personal or brand), media type (photos, videos or both), quantities, product type, creative brief, start date and preferred contact channel (email or WhatsApp)
- Interface available in **Arabic, English and French**

**Back office (EasyAdmin)**
- Manage clients, projects, quote requests, models, team members and users
- Quote workflow with statuses: pending, accepted, rejected
- Link each project to its client, models and team members

**Security**
- Registration with email verification
- CSRF-protected login and logout
- Password reset by email
- Role-based access control: `ROLE_ADMIN`, `ROLE_MANAGER`, `ROLE_PHOTOGRAPHER`

## Tech stack

| Layer | Technologies |
| --- | --- |
| Back end | PHP 8.2, Symfony 7.4, Doctrine ORM 3, Doctrine Migrations |
| Front end | Twig, Tailwind CSS, Symfony UX (Stimulus, Turbo), AssetMapper |
| Admin | EasyAdmin 5 |
| Database | MySQL |
| Other | Symfony Mailer, Symfony Translation, PHPUnit, Git |

## Data model

```mermaid
erDiagram
    CLIENT ||--o{ PROJECTS : orders
    PROJECTS }o--o{ MODELS : features
    PROJECTS }o--o{ TEAM_MEMBERS : staffs
    USER ||--o{ RESET_PASSWORD_REQUEST : requests
    QUOTE_REQUEST
```

## Getting started

Requirements: PHP 8.2+, Composer, MySQL, and optionally the [Symfony CLI](https://symfony.com/download).

```bash
git clone <this-repository-url> mini-soleil-agency
cd mini-soleil-agency
composer install

# Create .env.local with your own settings, for example:
# DATABASE_URL="mysql://user:password@127.0.0.1:3306/minisoleil?serverVersion=8.0"
# MAILER_DSN="smtp://user:password@smtp.example.com:587"

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console tailwind:build
symfony serve
```

Then open http://127.0.0.1:8000. To reach the back office at `/admin`, create an account and give it `ROLE_ADMIN` in the database.

## Author

**Arwa Alzahrani** — designer and full stack web developer
[arwacreates.com](https://arwacreates.com) · [LinkedIn](https://www.linkedin.com/in/arwakhaled)
