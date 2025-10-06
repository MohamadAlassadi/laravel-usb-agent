
<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

# Laravel USB Agent Project

تطبيق ويب باستخدام **Laravel 11+** للتواصل مع **Python Agent** على جهاز بعيد يحتوي على وحدة تخزين USB.  
يوفّر واجهة دخول (Login) ولوحة تحكم (Dashboard) لإدارة الملفات (رفع – تحميل – استعراض).

## المتطلبات

- PHP 8.3  
- Composer  
- MySQL فقط  
- Laravel 11+  
- Python 3.8+  

## خطوات التشغيل الكاملة

### 1 تحميل المشروع من GitHub
```bash
git clone https://github.com/MohamadAlassadi/laravel-usb-agent.git
cd laravel-usb-agent
```

### 2 تثبيت اعتمادات Laravel
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 3 إعداد قاعدة البيانات MySQL
1. أنشئ قاعدة بيانات جديدة باسم `usb_app`  
2. عدّل إعدادات الاتصال في ملف `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=usb_app
DB_USERNAME=root
DB_PASSWORD=
```
 شغّل المايجريشن والسيدرز:
```bash
php artisan migrate --seed
```
**المستخدم الافتراضي:** test@example.com / 123456

### 4 تعديل إعدادات الاتصال مع Python Agent
في ملف `.env`:
```
CLIENT_HOST=127.0.0.1
CLIENT_PORT=9000
```

### 5 تشغيل Python Agent
في ملف `usb_agent.py` عدّل مسار الفلاشة حسب جهازك:
```python
USB_PATH = r"F:\"
```
ثم شغّل السكربت:
```bash
python usb_agent.py
```

### 6 تشغيل Laravel
```bash
php artisan serve
```
التطبيق متاح على: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 7️⃣ الواجهات (Blade)
```php
Route::get('/login', [WebController::class, 'showLogin'])->name('login');
Route::get('/', [WebController::class, 'showLogin']);
Route::get('/dashboard', [WebController::class, 'showDashboard'])->name('dashboard');
Route::get('/logout', [WebController::class, 'logout'])->name('logout');
```
- `/login` → تسجيل الدخول  
- `/dashboard` → لوحة التحكم لرفع/تحميل واستعراض الملفات  
- `/logout` → تسجيل الخروج


