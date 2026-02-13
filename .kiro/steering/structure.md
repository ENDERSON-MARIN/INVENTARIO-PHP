# Project Structure

## Root Directory Layout

```
/app                    - Application core code
/bootstrap              - Framework bootstrap files
/config                 - Configuration files
/database               - Migrations, seeds, factories
/public                 - Web server document root
/resources              - Views, assets, language files
/routes                 - Route definitions
/storage                - Logs, cache, uploads
/tests                  - Test files
/vendor                 - Composer dependencies
```

## Application Layer (`/app`)

### Controllers (`/app/Http/Controllers`)

- All controllers extend CRUDBooster's `CBController`
- Naming convention: `Admin{Entity}Controller` (e.g., `AdminProductosController`)
- Custom methods follow pattern: `getAdd()`, `getEdit($id)`, `getDetail($id)`, `store()`, `destroy($id)`
- Controllers use Spanish naming for business entities (productos, ventas, compras, clientes, proveedores, categorias)

### Models (`/app/Models`)

- Use Eloquent ORM with soft deletes (`SoftDeletes` trait)
- Define `$table`, `$primaryKey`, `$timestamps`, `$fillable`, `$dates` properties
- Include relationship methods (belongsTo, hasMany, belongsToMany)
- Naming: Singular Spanish names (Producto, Venta, Compra, Cliente, Proveedor, Categoria)

### Requests (`/app/Http/Requests`)

- Form request validation classes
- Naming: `{Entity}FormRequest` (e.g., `ProductoFormRequest`)

### Middleware (`/app/Http/Middleware`)

- Standard Laravel middleware (Auth, CORS, CSRF, etc.)

## Database Layer (`/database`)

### Migrations

- CRUDBooster CMS tables (prefix: `cms_`)
- Business entity tables: categorias, productos, proveedores, clientes, compras, ventas
- Detail tables: compra_detalles, venta_detalles
- Use timestamps and soft deletes where applicable

### Dumps (`/database/dumps`)

- SQL dump files for database backup/restore
- Trigger definitions stored separately

## Configuration (`/config`)

- Standard Laravel configs plus CRUDBooster-specific configs
- Key files: `database.php`, `app.php`, `crudbooster.php`

## Naming Conventions

### Spanish Business Terms

- productos (products)
- ventas (sales)
- compras (purchases)
- clientes (customers)
- proveedores (suppliers)
- categorias (categories)

### Controller Methods

- `cbInit()` - CRUDBooster initialization
- `getAdd()` - Show create form
- `store()` - Handle create
- `getEdit($id)` - Show edit form
- `postEdit()` or custom update method - Handle update
- `getDetail($id)` - Show details
- `destroy($id)` - Handle delete
- Hook methods: `hook_before_add()`, `hook_after_add()`, `hook_before_edit()`, etc.

### Database Columns

- Common fields: `id`, `created_at`, `updated_at`, `deleted_at`
- Audit fields: `responsable_creacion`, `responsable_edicion`
- Foreign keys: `{entity}_id` (e.g., `categoria_id`, `cliente_id`)

## Key Patterns

### Transaction Handling

Always wrap database operations in transactions:

```php
try {
    DB::beginTransaction();
    // operations
    DB::commit();
    Alert::success('Message');
} catch(\Exception $e) {
    DB::rollBack();
    Alert::error('Error message');
}
```

### CRUDBooster Integration

- Controllers configure tables via `cbInit()` method
- Use `$this->col` for table columns
- Use `$this->form` for form fields
- Use `$this->cbView()` for rendering views
- Access current user: `CRUDBooster::myId()`

### Relationships

- Master-detail pattern for compras/ventas with their detalles
- Foreign key relationships defined in models
- Soft deletes enabled on main entities
