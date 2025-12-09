
# Laravel 11 – Product CRUD With **Live Search + Pagination** (AJAX Based)

This README explains ONLY the **LIVE SEARCH** and **AJAX Pagination** system used in the Product CRUD application.



---

# Step 1 — Install Laravel 11

```
composer create-project laravel/laravel example-app
```

---

# Step 2 — Configure Database

Update `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

# Step 3 — Create Products Table

```
php artisan make:migration create_products_table --create=products
```

Migration fields include:

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

# Step 4 — Add Resource Routes

```
Route::resource('products', ProductController::class);
```

---

# Step 5 — Product Model

```php
protected $fillable = [
  'name','details','price','size','color','category','image'
];
```

---

 MAIN FEATURE: LIVE SEARCH + PAGINATION (NO PRICE SORT)

Everything happens inside **ProductController@index()**.

---
 6.1 Controller — Live Search Logic

```php
if ($request->filled('keyword')) {

    $keyword = $request->keyword;

    if (is_numeric($keyword)) {

        // Number → search exact price
        $query->where('price', (float)$keyword);

    } else {

        // Text search across multiple fields
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

 Explanation  
- If user types a **number** → we search price  
- If user types **text** → we search name, category, size, color, details  
- This gives instant e-commerce like search

---

 6.2 Controller — PAGINATION Logic (Most Important)

```php
$products = $query->paginate(1)->appends($request->query());
```

 Why `.appends()` is used?  
So that when you click next page:

- The **same search keyword stays active**
- Pagination never resets your results  
- AJAX remembers your filters  

---

# Step 7 — Blade UI for Live Search + Pagination

 Search Input
```html
<input type="text" id="search" class="form-control" placeholder="Search products...">
```

 Pagination UI
Laravel automatically renders:

```blade
{{ $products->links() }}
```

---

# Step 8 — AJAX Code (LIVE SEARCH + PAGINATION ONLY)

No sorting code included.

```javascript
function fetch_data(page = 1, keyword = '') {

    $.ajax({
        url: "{{ route('products.index') }}",
        type: "GET",
        data: { page, keyword },
        success: function(data) {

            $('#product-table-wrapper')
              .html($(data).find('#product-table-wrapper').html());
        }
    });
}
```

---

 Live Search Trigger

```javascript
$('#search').on('keyup', function(){
    fetch_data(1, $('#search').val());
});
```

---

 Pagination Trigger

```javascript
$(document).on('click', '.pagination a', function(e){
    e.preventDefault();

    let page = $(this).attr('href').split('page=')[1];

    fetch_data(page, $('#search').val());
});
```

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
