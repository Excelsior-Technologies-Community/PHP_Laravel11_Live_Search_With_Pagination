
 Laravel 11 – Full Product CRUD with Live Search, Sorting, Pagination, Image Upload + Admin & Customer Panels

![Laravel](https://img.shields.io/badge/Laravel-11-orange)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![MySQL](https://img.shields.io/badge/Database-MySQL-yellow)
This documentation provides a **complete professional guide** for building a fully functional **Product Management System** in Laravel 11 that includes:

- ✔ CRUD (Create, Read, Update, Delete)
- ✔ Live Search (AJAX)
- ✔ Sorting (Price Low → High, High → Low)
- ✔ Pagination (AJAX powered)
- ✔ Image Upload with previews
- ✔ Admin Panel (Bootstrap layout)
- ✔ Laravel Breeze Authentication (Login/Register)
- ✔ Customer Products Page
- ✔ Full Controller + Blade + Routes explanation

---

 Features Overview

| Feature | Description |
|--------|-------------|
| **Live Search** | Search by name, category, color, size, details, or price |
| **Dynamic Sorting** | Price ascending / descending |
| **Pagination** | AJAX pagination preserving search filters |
| **Image Upload** | Stores product image inside `/public/images/` |
| **Admin CRUD Panel** | Create, edit, delete products |
| **Customer Panel** | Displays products publicly with design |
| **Authentication** | Laravel Breeze login & register |

---

 Project Structure (Important Folders)
```
/app
  /Models/Product.php
  /Http/Controllers/ProductController.php
/resources
  /views/products/index.blade.php
  /views/products/create.blade.php
  /views/products/edit.blade.php
/public/images
/database/migrations/create_products_table.php
/routes/web.php
```

---

 Step 1: Install Laravel 11

```
composer create-project laravel/laravel example-app
```

---
 Step 2: Configure MySQL

Modify `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

 Step 3: Create Products Migration

Migration includes:

- name  
- details  
- price  
- size  
- color  
- category  
- image  

Run migration:

```
php artisan migrate
```

---

 Step 4: Resource Route

```
Route::resource('products', ProductController::class);
```

---

 Step 5: ProductController (FULL LOGIC INCLUDED)

 Live Search Logic Explained

```
if ($request->filled('keyword')) {
    if (is_numeric($keyword)) {
        $query->where('price', (float)$keyword);
    } else {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('category', 'like', "%{$keyword}%")
              ->orWhere('color', 'like', "%{$keyword}%")
              ->orWhere('size', 'like', "%{$keyword}%")
              ->orWhere('details', 'like', "%{$keyword}%");
        });
    }
}
```

 Sorting Logic

```
if ($request->sort == 'price-asc')  orderBy('price', 'asc');
if ($request->sort == 'price-desc') orderBy('price', 'desc');
```

 Pagination

```
$products = $query->paginate(1)->appends($request->query());
```

 Image Upload

```
$imageName = time().'_'.uniqid().'.'.$request->image->extension();
$request->image->move(public_path('images'), $imageName);
```

---

 Step 6: Blade Files (Frontend)

 index.blade.php (LIVE SEARCH + SORT + PAGINATION)

Includes:

- Search input
- Sorting dropdown
- AJAX pagination
- Table with product details

 AJAX Script Example

```
$('#search').keyup(function(){
    fetch_data(1, $('#search').val(), $('#sort').val());
});

$('#sort').change(function(){
    fetch_data(1, $('#search').val(), $('#sort').val());
});
```

---

 create.blade.php (Create Product Form)

Includes:

- Name
- Details
- Size
- Color
- Category
- Price
- Single Image Upload

---

 edit.blade.php (Update Product)

Includes:

- Old image preview
- Option to upload new image
- Update details

---

 Step 7: Admin Panel Layout

Uses:

- Bootstrap 5
- Navigation bar
- Container layout

File: `resources/views/layouts/admin.blade.php`

---

 Laravel Breeze Authentication Setup

Install Breeze:

```
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
php artisan migrate
```

Protect product routes:

```
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

After login → Redirect to products:

```
public const HOME = '/products';
```

---

 Customer Product Page (Frontend)

Route:

```
Route::get('/customer/products', [CustomerProductsController::class, 'index'])
    ->name('customer.products');
```

Blade view includes:

- Product image
- Category, size, color
- Price formatting
- Card layout (Bootstrap)

---

 Run Project

```
php artisan serve
```

Visit admin CRUD:

```
http://localhost:8000/products
```
<img width="1054" height="237" alt="image" src="https://github.com/user-attachments/assets/a9fec3bb-37c2-41b6-bdfe-fd2001128d12" />
<img width="676" height="151" alt="image" src="https://github.com/user-attachments/assets/d643f305-dc04-4141-97d6-0a61606aff3b" />
```

---
