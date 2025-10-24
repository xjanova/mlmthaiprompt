# ThaiPrompt - แพลตฟอร์ม SaaS Multi-Tenant สำหรับธุรกิจ

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.9-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11.9">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-5.0-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

## 📖 เกี่ยวกับโปรเจค

**ThaiPrompt** เป็นแพลตฟอร์ม SaaS (Software as a Service) แบบ Multi-Tenant ที่ออกแบบมาเพื่อรองรับธุรกิจขนาดเล็กถึงกลาง โดยรวมระบบการจัดการหลากหลายด้านไว้ในแพลตฟอร์มเดียว ได้แก่ ระบบบัญชี, CRM, HR, จัดการโปรเจค, ขายหน้าร้าน (POS) และอื่นๆ อีกมากมาย

**เว็บไซต์:** [user.thaiprompt.online](https://user.thaiprompt.online)

### ✨ จุดเด่นหลัก

- 🏢 **Multi-Tenant Workspace** - รองรับหลาย Workspace ในบัญชีเดียว
- 🔐 **ระบบสิทธิ์ RBAC** - จัดการบทบาทและสิทธิ์การเข้าถึงอย่างละเอียด
- 💳 **รองรับหลาย Payment Gateway** - Stripe, PayPal, Braintree, Midtrans, Mollie, Xendit, และอื่นๆ
- 🌐 **Multi-Language** - รองรับมากกว่า 15 ภาษา รวมถึงภาษาไทย
- 🤖 **AI Integration** - ผสานระบบ OpenAI สำหรับสร้างเนื้อหาอัตโนมัติ
- 📊 **Dashboard & Analytics** - แดชบอร์ดและรายงานแบบเรียลไทม์
- 🎨 **White-Label Ready** - รองรับ Custom Domain และปรับแต่งแบรนด์ได้
- 💰 **MLM System (ครบวงจร)** - ระบบ Multi-Level Marketing แบบครบวงจร
  - โครงสร้างเครือข่าย Binary/Unilevel
  - ระบบค่าคอมมิชชั่นหลายระดับ (Direct, Indirect, Binary, Matching)
  - ระบบจัดการ Rank/ตำแหน่ง
  - ระบบจ่ายเงิน (Payout) อัตโนมัติ
  - ระบบโบนัสหลากหลายรูปแบบ
  - Genealogy Tree & Network Statistics
  - รายงานและ Dashboard แบบเรียลไทม์
- 📱 **Responsive Design** - ใช้งานได้บนทุกอุปกรณ์

---

## 🎯 ฟีเจอร์หลัก

### 📦 โมดูลหลัก

#### 1. **ProductService** - จัดการสินค้า
- จัดการแคตตาล็อกสินค้า หมวดหมู่ หน่วยนับ
- ระบบคลังสินค้า ติดตามสต็อก
- สร้างคำอธิบายสินค้าด้วย AI

#### 2. **Taskly** - จัดการโปรเจค
- บริหารโปรเจคและงาน
- ทำงานร่วมกันเป็นทีม
- จัดการ Workspace

#### 3. **Account** - ระบบบัญชี
- สร้างและจัดการใบแจ้งหนี้ (Invoice)
- ใบเสนอราคา (Proposal)
- ใบสั่งซื้อ (Purchase Order)
- ติดตามการชำระเงิน
- ใบลดหนี้ (Debit Note)
- รายงานทางการเงิน

#### 4. **Lead** - ระบบ CRM
- จัดการลูกค้าเป้าหมาย
- บริหารติดต่อและลูกค้า
- ติดตาม Pipeline
- เชื่อมต่อ HubSpot

#### 5. **HRM** - ทรัพยากรบุคคล
- จัดการพนักงาน
- ติดตามผลงาน
- บริหารการฝึกอบรม
- ใบอนุญาตทำงาน

#### 6. **POS** - ขายหน้าร้าน
- ระบบขายปลีก Point of Sale
- ติดตามยอดขาย

#### 7. **LandingPage** - จัดการเว็บไซต์
- สร้าง Landing Page
- ระบบ CMS
- จัดการเนื้อหาเว็บไซต์

#### 8. **MLM System** - ระบบ Multi-Level Marketing (ใหม่!)
- **โครงสร้างเครือข่าย:**
  - รองรับ Binary Tree (ขาซ้าย-ขวา)
  - รองรับ Unilevel (ไม่จำกัดจำนวน)
  - Genealogy Tree แบบไดนามิก
  - การจัดวางอัตโนมัติ (Auto Placement)

- **ระบบค่าคอมมิชชั่น:**
  - Direct Commission (ค่าคอมโดยตรง)
  - Indirect Commission (ค่าคอมทางอ้อม หลายระดับ)
  - Binary Commission (ค่าคอมจาก Binary Matching)
  - Leadership Commission (ค่าคอมผู้นำ)
  - Matching Bonus
  - สามารถตั้งค่า % และเงื่อนไขได้

- **ระบบจัดการตำแหน่ง (Ranks):**
  - สร้างตำแหน่งได้ไม่จำกัด (Bronze, Silver, Gold, Diamond, etc.)
  - กำหนดเกณฑ์การได้ตำแหน่ง
  - โบนัสรายเดือนตามตำแหน่ง
  - ติดตามประวัติการเลื่อนตำแหน่ง

- **ระบบจ่ายเงิน (Payout):**
  - ขอถอนเงินอัตโนมัติ
  - รองรับหลายวิธีการจ่าย (Bank, PayPal, Stripe, Crypto)
  - ระบบอนุมัติและติดตาม
  - ค่าธรรมเนียมแบบยืดหยุ่น

- **รายงานและสถิติ:**
  - Dashboard MLM แบบเรียลไทม์
  - รายงานค่าคอมมิชชั่น
  - รายงานยอดขายทีม
  - สถิติเครือข่าย (ซ้าย-ขวา)
  - ประวัติกิจกรรมทั้งหมด

### 🛠️ ฟีเจอร์เสริม

- 🎫 ระบบ Helpdesk / Support Ticket
- 📧 Email Template Management
- 🔔 Notification System แบบหลายช่องทาง
- 🏦 Bank Transfer Payment Request
- 📦 Warehouse Management & Transfer
- 💬 Messenger / Chat (Chatify)
- 📊 Import/Export Excel, CSV
- 💱 Multi-Currency Support
- 🔒 Google 2FA Authentication
- 📱 SMS Notifications (Twilio, Vonage, etc.)

---

## 🏗️ เทคโนโลジีที่ใช้

### Backend
- **Framework:** Laravel 11.9
- **PHP:** 8.3+
- **Database:** MySQL 8.0+ / SQLite
- **Authentication:** Laravel Sanctum, JWT, Google 2FA
- **RBAC:** Laratrust
- **Queue:** Database Queue
- **Cache:** Database / Redis

### Frontend
- **Build Tool:** Vite 5.0
- **CSS Framework:** Tailwind CSS 3.1
- **JavaScript:** Alpine.js 3.4, Axios
- **Components:** Blade Templates

### บริการและ API ภายนอก
- **Payment:** Stripe, PayPal, Braintree, Midtrans, Mollie, Xendit, Authorize.net, Coingate, PhonePe, FedaPay
- **AI:** OpenAI API
- **Storage:** AWS S3, Dropbox
- **Email:** Mailchimp, SMTP, Mailgun, Mailtrap
- **SMS:** Twilio, Vonage, Kavenegar
- **Analytics:** Google Analytics
- **Office:** Microsoft Graph, PhpSpreadsheet
- **Social:** OAuth via Socialite

### DevOps
- **CI/CD:** GitHub Actions
- **Package Manager:** Composer (PHP), NPM (Node.js)
- **Testing:** PHPUnit 11
- **Code Quality:** Laravel Pint

---

## 📋 ความต้องการของระบบ

### สำหรับ Development
- PHP >= 8.3
- Composer >= 2.0
- Node.js >= 20.x
- NPM >= 10.x
- MySQL 8.0+ หรือ SQLite
- Git

### PHP Extensions ที่ต้องการ
```
- mbstring
- xml
- curl
- zip
- intl
- redis (optional)
- pdo_mysql
- gd
- fileinfo
- openssl
```

### สำหรับ Production
- Apache/Nginx with SSL
- PHP 8.3+ with OPCache
- MySQL 8.0+
- Redis (recommended)
- Supervisor (for queues)
- Node.js 20+ (for asset compilation)

---

## 🚀 การติดตั้งและตั้งค่า

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/mlmthaiprompt.git
cd mlmthaiprompt
```

### 2. ติดตั้ง Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
# หรือ
npm ci
```

### 3. ตั้งค่า Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### 4. ตั้งค่าฐานข้อมูล

แก้ไขไฟล์ `.env`:
```env
APP_NAME="ThaiPrompt"
APP_ENV=local
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thaiprompt
DB_USERNAME=root
DB_PASSWORD=

# สำหรับ Development (SQLite)
# DB_CONNECTION=sqlite
```

### 5. Migration และ Seed
```bash
# สร้างฐานข้อมูล SQLite (ถ้าใช้ SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Run seeders (optional)
php artisan db:seed
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. สร้าง Symbolic Link สำหรับ Storage
```bash
php artisan storage:link
```

### 8. ตั้งค่า Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### 9. เริ่มต้นใช้งาน
```bash
# Development server
php artisan serve

# เข้าใช้งานที่ http://localhost:8000
```

---

## 📦 Deployment

### Automated Deployment via GitHub Actions

โปรเจคนี้มีระบบ CI/CD อัตโนมัติผ่าน GitHub Actions:

1. **Push ไปที่ branch `main`** - จะทริกเกอร์ deployment อัตโนมัติ
2. **Manual Trigger** - สามารถรัน workflow ด้วยตนเองได้

#### ขั้นตอน CI/CD:
```yaml
1. Build & Test
   - Setup PHP 8.3 + Extensions
   - Composer install
   - Setup Node.js 20
   - NPM install & build
   - PHP syntax check

2. Deploy
   - SSH to production server
   - Run deploy.sh script
```

### Manual Deployment

สำหรับ deploy แบบ manual บนเซิร์ฟเวอร์:

```bash
# SSH เข้าเซิร์ฟเวอร์
ssh admin@user.thaiprompt.online

# ไปที่ directory
cd /home/admin/domains/user.thaiprompt.online/laravel

# รัน deployment script
bash ./deploy.sh
```

### ขั้นตอนใน deploy.sh:
1. ⏸️ เปิด maintenance mode (`php artisan down`)
2. 🔄 Pull code จาก Git (`git pull origin main`)
3. 📦 Install dependencies (`composer install --no-dev`)
4. 🧹 Clear caches (`php artisan optimize:clear`)
5. 🔍 Discover packages
6. 🎨 Build frontend assets (`npm run build`)
7. 🔐 Fix permissions
8. 💾 Cache config/routes/views
9. ✅ ปิด maintenance mode (`php artisan up`)

### Production Configuration

**เซิร์ฟเวอร์:** user.thaiprompt.online
**App Directory:** `/home/admin/domains/user.thaiprompt.online/laravel`
**Public Directory:** `/home/admin/domains/user.thaiprompt.online/laravel/public`
**PHP Version:** 8.3
**Web Server:** Apache with mod_rewrite

#### ตัวแปร Environment ที่จำเป็น
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://user.thaiprompt.online

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

#### GitHub Secrets ที่ต้องตั้งค่า
```
SERVER_HOST       → IP หรือ domain ของเซิร์ฟเวอร์
SERVER_USER       → SSH username
SERVER_SSH_KEY    → SSH private key
```

---

## 📁 โครงสร้างโปรเจค

```
mlmthaiprompt/
├── app/                        # Application core
│   ├── Http/
│   │   ├── Controllers/        # Controllers
│   │   ├── Middleware/         # Middleware
│   │   ├── Requests/           # Form Requests
│   │   └── DataTables/         # DataTables
│   ├── Models/                 # Eloquent Models
│   ├── Events/                 # Events
│   ├── Listeners/              # Event Listeners
│   ├── Mail/                   # Email Classes
│   ├── Classes/                # Utilities (Module, Menu, Setting)
│   └── Helper/                 # Helper functions
│
├── addon/            # Feature Modules
│   ├── ProductService/         # จัดการสินค้า
│   ├── Taskly/                 # จัดการโปรเจค
│   ├── Account/                # ระบบบัญชี
│   ├── Lead/                   # CRM
│   ├── Hrm/                    # HR
│   ├── Pos/                    # Point of Sale
│   ├── LandingPage/            # Website Builder
│   ├── Stripe/                 # Stripe Payment
│   └── Paypal/                 # PayPal Payment
│
├── app/Services/               # Business Logic Services
│   ├── MlmNetworkService.php   # MLM Network Management
│   ├── MlmCommissionService.php # MLM Commission Calculation
│   └── [other services...]
│
├── resources/
│   ├── views/                  # Blade Templates
│   ├── js/                     # JavaScript
│   ├── css/                    # Stylesheets
│   └── lang/                   # Language files (15+ languages)
│
├── routes/
│   ├── web.php                 # Web Routes
│   ├── api.php                 # API Routes
│   └── auth.php                # Auth Routes
│
├── database/
│   ├── migrations/             # Database Migrations (78 files)
│   ├── seeders/                # Database Seeders
│   └── factories/              # Model Factories
│
├── config/                     # Configuration files
├── storage/                    # App Storage
├── public/                     # Public assets
├── tests/                      # Test suite
├── .github/workflows/          # GitHub Actions CI/CD
├── deploy.sh                   # Deployment script
├── composer.json               # PHP dependencies
├── package.json                # Node dependencies
├── vite.config.js              # Vite configuration
├── tailwind.config.js          # Tailwind configuration
└── README.md                   # This file
```

---

## 👨‍💻 สำหรับนักพัฒนา

### Development Workflow

1. **สร้าง Branch ใหม่**
```bash
git checkout -b feature/your-feature-name
```

2. **Development**
```bash
# เปิด dev server พร้อม hot reload
npm run dev

# เปิด Laravel dev server
php artisan serve
```

3. **Testing**
```bash
# Run tests
php artisan test

# หรือใช้ PHPUnit
./vendor/bin/phpunit
```

4. **Code Quality**
```bash
# Format code ด้วย Laravel Pint
./vendor/bin/pint
```

### ทำงานกับ Modules

สร้างโมดูลใหม่:
```bash
php artisan make:module YourModuleName
```

โมดูลจะถูกสร้างใน `addon/YourModuleName/`

### Artisan Commands ที่มีประโยชน์

```bash
# Clear all caches
php artisan optimize:clear

# Cache config, routes, views
php artisan optimize

# Discover packages
php artisan package:discover

# Run queues
php artisan queue:work

# Create new migration
php artisan make:migration create_your_table

# Create model
php artisan make:model YourModel

# Create controller
php artisan make:controller YourController
```

### Database Management

```bash
# Run migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Fresh migration (ลบและสร้างใหม่)
php artisan migrate:fresh

# Seed database
php artisan db:seed
```

### Debug Mode

แก้ไข `.env`:
```env
APP_DEBUG=true
```

เปิด Laravel Debugbar:
```env
DEBUGBAR_ENABLED=true
```

---

## 🔐 Security

### Best Practices

1. **เปลี่ยน APP_KEY** ทุกครั้งที่ deploy ครั้งแรก
2. **ตั้งค่า APP_DEBUG=false** ใน production
3. **ใช้ HTTPS** สำหรับ production
4. **เปิดใช้ Google 2FA** สำหรับ admin
5. **อัพเดท dependencies** เป็นประจำ
6. **Backup ฐานข้อมูล** เป็นประจำ
7. **ตรวจสอบ logs** ที่ `storage/logs/`

### การรายงานช่องโหว่

หากพบช่องโหว่ด้านความปลอดภัย กรุณาติดต่อทีม security ที่:
- Email: security@thaiprompt.online

---

## 📊 Database Schema

โปรเจคใช้ 78 migration files และ 44 Eloquent models

**โมเดลหลัก:**
- User, Workspace
- Plan, Coupon
- Invoice, Proposal, Purchase
- HelpdeskTicket
- EmailTemplate
- Currency, Language
- ReferralTransaction (MLM)
- Warehouse, WarehouseTransfer
- และอื่นๆ อีกมากมาย

---

## 🌍 Localization

รองรับภาษา:
- 🇹🇭 ไทย
- 🇬🇧 อังกฤษ
- 🇯🇵 ญี่ปุ่น
- 🇩🇪 เยอรมัน
- 🇸🇦 อาหรับ
- 🇧🇷 โปรตุเกส (บราซิล)
- และอื่นๆ อีก 10+ ภาษา

เพิ่มภาษาใหม่:
```bash
# สร้างไฟล์ภาษาใน resources/lang/
cp resources/lang/en.json resources/lang/your_lang.json
```

---

## 📞 การสนับสนุน

- **Documentation:** [อยู่ระหว่างการพัฒนา]
- **Issues:** [GitHub Issues](https://github.com/yourusername/mlmthaiprompt/issues)
- **Email:** support@thaiprompt.online
- **Website:** [user.thaiprompt.online](https://user.thaiprompt.online)

---

## 👥 ทีมพัฒนา

**ThaiPrompt Development Team**

สร้างด้วยใจโดยทีม ThaiPrompt 🇹🇭

---

## 📝 License

โปรเจคนี้เผยแพร่ภายใต้ [MIT License](https://opensource.org/licenses/MIT)

---

## 🙏 Acknowledgments

ขอขอบคุณ:
- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Alpine.js](https://alpinejs.dev) - JavaScript Framework
- ชุมชน Open Source ทั้งหมดที่สนับสนุนโปรเจคนี้

---

## 📈 Changelog

### v2.0.0 - MLM System Release 🎉
- ✅ **เพิ่มระบบ MLM แบบครบวงจร**
  - 6 Database Tables ใหม่ (Networks, Ranks, Commissions, Payouts, Bonuses, Genealogy)
  - 8 Eloquent Models พร้อม Relations
  - 2 Service Classes สำหรับ Business Logic
  - รองรับ Binary & Unilevel Structure
  - ระบบค่าคอมมิชชั่นหลายระดับ
  - ระบบจัดการตำแหน่งและโบนัส

### v1.1.0
- ✅ **เปลี่ยนโครงสร้าง:** packages/workdo → addon
- ✅ อัปเดตโค้ดล่าสุดจากเซิร์ฟเวอร์
- ✅ ปรับปรุง deployment workflow
- ✅ ซิงค์ไฟล์ configuration
- ✅ เพิ่ม CI/CD automation
- ✅ เขียน README ใหม่ทั้งหมด

---

<p align="center">
Made with ❤️ by ThaiPrompt Team
</p>
