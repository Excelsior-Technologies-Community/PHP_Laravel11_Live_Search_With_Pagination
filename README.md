# 🔍 Laravel 11 – Live Search + Pagination + Sorting System  
![Laravel](https://img.shields.io/badge/Laravel-11-orange)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![MySQL](https://img.shields.io/badge/Database-MySQL-yellow)

This documentation explains how to create a **Live Search, Sorting & Pagination System** in Laravel 11.  
It includes a full Product CRUD with image upload, admin panel, and customer product page.

---

# ⭐ Features
- Live Search (AJAX)
- Price Sorting (Low → High, High → Low)
- Pagination with preserved filters
- Product CRUD
- Single image upload
- Customer product display
- Admin panel layout
- Breeze authentication support

---

# 🧱 Step 1 — Install Laravel 11

```
composer create-project laravel/laravel example-app
```

---

# 🛠 Step 2 — Database Configuration

Update `.env`:

```
DB_CONNECTION=mysql
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

# 🧱 Step 3 — Product Migration

```php
$table->string('name');
$table->text('details');
$table->decimal('price', 8, 2);
$table->string('size');
$table->string('color');
$table->string('category');
$table->string('image')->nullable();
```

Run:

```
php artisan migrate
```

---

# 🧠 Step 4 — Routes

```php
Route::resource('products', ProductController::class);
```

---

# 🧠 Step 5 — Product Model

```php
protected $fillable = [
    'name','details','price','size','color','category','image'
];
```

---

# 🚀 Step 6 — ProductController (Live Search + Sorting + Pagination)

### ✔ LIVE SEARCH  
Searches across:
- name  
- category  
- color  
- size  
- details  
- price (exact match)

### ✔ SORTING  
- price-asc  
- price-desc  

### ✔ PAGINATION  
```php
$products = $query->paginate(1)->appends($request->query());
```

---

# 🎨 Step 7 — Index Page (AJAX Search + Pagination)

UI includes:
- Search box  
- Sorting dropdown  
- Filter button  
- AJAX-powered updates  
- Pagination  
- Image preview  
- Edit/Delete buttons  

AJAX Script:
```js
$('#search').on('keyup', function(){
    fetch_data(1, $('#search').val(), $('#sort').val());
});
```

---

# 🎨 Step 8 — Create & Edit Product Pages

Includes:
- Name  
- Details  
- Image upload  
- Size  
- Color  
- Category  
- Price  
- Preview of current image in Edit page  

Image upload:
```php
$imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
$request->image->move(public_path('images'), $imageName);
```

---

# 👤 Step 9 — Customer Product Page

```
Route::get('/customer/products', [CustomerProductsController::class, 'index']);
```

Displays:
- Image
- Name
- Short details
- Category
- Size, Color
- Price

---

# 🎨 Step 10 — Admin & Customer Layouts

### ✔ Admin Layout
- Bootstrap UI
- Navigation
- Header
- Content container

### ✔ Customer Layout
- Product grid  
- Fixed card height  
- Clean simple UI  

---

# 🔐 Step 11 — Breeze Authentication

```
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
php artisan migrate
```

Protect routes:
```php
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

Login redirect:
```
public const HOME = '/products';
```

---

# ▶ Run Application

```
php artisan serve
```

Admin URL:
```
http://localhost:8000/products

<img width="1054" height="237" alt="image" src="https://github.com/user-attachments/assets/a9fec3bb-37c2-41b6-bdfe-fd2001128d12" />
<img width="676" height="151" alt="image" src="https://github.com/user-attachments/assets/d643f305-dc04-4141-97d6-0a61606aff3b" />

