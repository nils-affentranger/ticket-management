## Was ist Eloquent?

Eloquent ist das integrierte [[ORM]] (Object-Relational Mapping) Framework von [[Laravel]]. Es ermöglicht die Interaktion mit Datenbanktabellen über ein objektorientiertes Modell, ohne direktes SQL schreiben zu müssen.

> [!info] Offizielle Dokumentation
> [Laravel Eloquent](https://laravel.com/docs/12.x/eloquent)

## Grundkonzepte

### Models
- Jedes Eloquent-Model repräsentiert eine Tabelle in der Datenbank
- Models befinden sich typischerweise im `App\Models` Verzeichnis

```php
// App\Models\User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Model-Logik hier
}
```

### Konventionen
- **Tabellennamen**: Plural und Snake Case (`users`, `blog_posts`)
- **Primärschlüssel**: Standardmäßig `id`
- **Timestamps**: `created_at` und `updated_at` werden automatisch verwaltet

>[!tip] Überschreiben der Standarde
>```php
>protected $table = 'my_users';        // Tabellennamen überschreiben
>protected $primaryKey = 'user_id';    // Primärschlüssel ändern
>public $timestamps = false;           // Timestamps deaktivieren
>```

## CRUD-Operationen

### Create
```php
// Alle Einträge abrufen
$users = User::all();

// Einzelnen Eintrag nach ID finden
$user = User::find(1);

// Mit Bedingungen abfragen
$activeUsers = User::where('active', 1)->get();
```

### Read
```php
// Methode 1: Neues Modell erstellen und speichern
$user = new User;
$user->name = 'Max Mustermann';
$user->email = 'max@example.com';
$user->save();

// Methode 2: Mass Assignment mit create()
$user = User::create([
    'name' => 'Max Mustermann',
    'email' => 'max@example.com'
]);
```

>[!warning] 
>Felder müssen in der `$fillable` Property im Model deklariert werden:
>```php
>protected $fillable = ['name', 'email'];
>```

### Update
```php
$user = User::find(1);
$user->email = 'neuemail@example.com';
$user->save();
```

### Delete
```php
// Methode 1: Model-Instanz löschen
$user = User::find(1);
$user->delete();

// Nach Bedingung löschen
User::where('active', 0)->delete();
```

## Beziehungen

### Arten von Beziehungen
- **One To One**: `hasOne()` und `belongsTo()`
- **One To Many**: `hasMany()` und `belongsTo()`
- **Many To Many**: `belongsToMany()`

```php
// Beispiel: One To Many (Posts eines Users)
// In User.php
public function posts()
{
    return $this->hasMany(Post::class);
}

// In Post.php
public function user()
{
    return $this->belongsTo(User::class);
}
```