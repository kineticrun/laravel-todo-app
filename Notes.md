# Jegyzetek - Laravel Lab Kickoff

## `1. Projekt felépítés`

- **app/**  
  Az alkalmazás üzleti logikájának nagy része található itt.  
  Ide tartoznak a modellek, controllerek, szolgáltatások és egyéb osztályok, amelyek a fő funkciókat valósítják meg.

- **database/**  
  - **migrations/**  
    Az adatbázis táblák szerkezetét leíró migrációs fájlok. Segítik az adatbázis verziókövetését és automatizált módosítását.  
  - **seeders/**  
    Tesztadatokat tartalmazó osztályok, amikkel fejlesztés vagy demo célra tölthetsz fel példány adatokat az adatbázisba.

- **resources/**  
  Az alkalmazás forrásállományai, amiket a build eszközök (pl. Vite) feldolgoznak.  
  - **js/** és **css/** fájlok itt találhatók (nyers formában).  
  - **views/**  
    Blade sablonok, amelyekből a szerver HTML oldalakat generál a böngésző felé.

- **routes/**  
  Itt definiáljuk az útvonalakat (route-okat), amelyek megmondják, hogy az adott URL-re milyen válasz érkezzen.  
  A `web.php` fájlban vannak a webes (HTML-t visszaadó) útvonalak, de van külön `api.php` is az API végpontoknak.

## `2. Routes & Views`

Egy weboldalon minden különböző URL egy-egy másik HTML oldalt jeleníthet meg.

### Alapfogalmak

Az URL első része a domain név (pl. `example.com`), amely a legtöbb esetben minden oldalon azonos.

A domain utáni részt, amely általában `/` jellel kezdődik, útvonalnak vagy route path-nak nevezzük (pl. `/about`).

Amikor a felhasználó kattint egy linkre (pl. a "Rólunk" oldalra), a böngésző GET kérést küld az adott URL-re, például `example.com/about`.

### Hogyan válaszol a Laravel egy kérésre?

Laravelben az útvonalak kezelését a `routes/web.php` fájlban végezzük.

Például:

```php
Route::get('/', function () {
    return view('welcome');
});
```

Ez azt jelenti, hogy:

1. A böngésző egy **GET** kérést küld a `/` útvonalra.
2. Laravel meghívja a megadott függvényt (a második argumentumot).
3. A függvény visszaad egy nézetet (view), jelen esetben a `welcome` nevűt.

### A `view()` függvény

- A `view()` függvény egy adott nézetet (blade fájlt) tölt be a `resources/views` könyvtárból.
- A megadott nézet nevét kiterjesztés nélkül adjuk meg (pl. `welcome`, nem `welcome.blade.php`).
- Laravel automatikusan a `.blade.php` kiterjesztésű fájlt keresi (pl. `welcome.blade.php`).

### Blade sablonmotor

- A Blade a Laravel saját sablonmotorja, amely lehetővé teszi dinamikus tartalmak megjelenítését a nézetekben.

### Példa dinamikus értékre:
```lang="{{ str_replace('_', '-', app()->getLocale()) }}"```
- A ```{{ }}``` Blade szintaxis automatikusan kiírja a benne lévő kifejezés eredményét HTML-ben.

### Saját route és nézet létrehozása
- Tegyük fel, hogy készül egy CRUD alkalmazás a kedvenc filmekről.
- Létrehozunk egy új almappát a resources/views könyvtárban, például: ```resources/views/movies/```
- Ebbe az almappába kerülnek a nézetek pl: ```index.blade.php```

- A route definíció a ```web.php``` fájlban
 ```php 
Route::get('moives', function () { 
    return view('moives.index'); 
});
```

- Fontos: Ha alamppák használata a nézeteknél a helyes név megadási konvenció az az, hogy a nézet nevét ponttal elválasztva kell megadni (pl. moives.index), nem pedig / jellel

## `3. Route Wildcards & View Data`

### 1. Adat átadása a View-nek

Laravelben a `view()` függvény második argumentumaként egy **asszociatív tömböt** adhatunk meg, mellyel adatokat továbbítunk a Blade nézetnek.

### Példa:

```php
return view('welcome', ['name' => 'John']);
<h1>Üdv, {{ $name }}!</h1>
```

- Fontos:
- A ```{{ $valtozo }}``` Blade szintaxis automatikusan HTML-escaped → a speciális karaktereket ártalmatlanítja (pl. <, >).

- Ez megvéd a XSS támadásoktól.

- Ha valamiért nyersen akarod megjeleníteni az adatot (nem ajánlott, csak megbízható forrásnál):

``` {!! $valtozo !!} ```

### `2. Route Wildcards – Dinamikus útvonal paraméterek7
Laravelben a route URL-ek tartalmazhatnak helyettesítő (wildcard) részeket, melyek dinamikus értékeket fogadnak be.

Példa route:
```php
Route::get('/movies/{id}', function ($id) {
    return "A film azonosítója: " . $id;
});
```
Mit csinál?
- A ```{id}``` egy wildcard paraméter, amit automatikusan átad a Laravel a route-hoz tartozó függvénynek.
- Ha a felhasználó a /movies/42 URL-re megy, akkor $id = 42 lesz.
- Controller használata valós projekteknél gyakran nem a route closure-ben kezeljük a logikát, hanem vezérlőben:

```php
Route::get('/movies/{id}', [MovieController::class, 'show']);

// MovieController.php
public function show($id)
{
    return view('movies.show', ['id' => $id]);
}
```

## `4. Blade directives - Balde alap vezérlőszerkezetek`

A Blade direktívák lehetőséget biztosítanak arra, hogy logikai műveleteket és vezérlési szerkezeteket alkalmazzunk a nézetfájlokban. Ezek segítségével például feltételes tartalmak jeleníthetők meg, illetve listákon történő iterálás is egyszerűen megvalósítható.

Minden Blade direktíva `@` karakterrel kezdődik, ezt követi a direktíva neve. A vezérlési szerkezetek általában kezdő- és záródirektívát is tartalmaznak. Például:

```php
@if($greeting == 'Hello')
    <!-- HTML kód -->
@endif
```
### Gyakori Blade direktívák:
- @if / @elseif / @else / @endifFeltételes\
logikák kezelésére szolgálnak.

- @foreach / @endforeach\
Tömbök vagy kollekciók bejárására használható. Az alábbi példa minden filmcímet megjelenít egy `<h1>` elemben:

```php
@foreach($movies as $movie)
    <h1>A film címe: {{ $movie['title'] }}</h1>
@endforeach
```

A fenti kód minden egyes $movie elem esetén kiírja annak címét. A {{ }} szintaxis a változók biztonságos, automatikusan HTML-escape-elt megjelenítését szolgálja.

## `5. Layouts & Slots – Laravel komponensek alapjai`

A komponensek létrehozásával rögzített elrendezés adható meg, például az alap HTML váz egy layout.blade.php komponensben definiálható. A nézetfájlokban az elrendezés újbóli létrehozása nem szükséges, hanem az adott komponens meghívásával történik:

```html
<x-layout>
    Ide jön a HTML tartalom
</x-layout>
```
A layout komponensben a {{ $slot }} direktíva helyettesíti be a komponenshívás közötti tartalmat.

A komponensek egymásba ágyazhatók korlátlanul, mely megkönnyíti a kód átláthatóságát és tisztaságát, valamint elősegíti az újrafelhasználhatóságot, ezzel csökkentve a redundanciát.

Lehetőség van név szerint megjelölt slotok definiálására is, például:

```html
<x-slot:title>
    Az oldal címe
</x-slot:title>
```

A komponensben a név szerint megadott slot a megfelelő helyen jeleníthető meg, például:

```html
<title>{{ $title }}</title>
```

Ezáltal egy adott nézethez testreszabhatóak a megjelenítési elemek és elnevezések. A layout komponens fejlécrészében a ```<title>``` elem például a title nevű slotot használja, míg az alapértelmezett slot ($slot) a fő tartalom megjelenítéséért felel.

Slotok használatakor célszerű modern PHP null- és nem definiált érték ellenőrző operátort (??) alkalmazni alapértelmezett érték megadására:

```html
<title>{{ $title ?? 'Unknown title' }}</title>
```
Ez megakadályozza a hibákat, ha egy név szerint definiált slot nem kerül megadásra.

## `6. Components, Attributes & Props`

### Újrafelhasználható komponensek
Laravel Blade lehetőséget ad arra, hogy saját komponenseket hozzunk létre, melyek újrafelhasználhatók és testre szabhatók különböző attribútumokkal és ún. prop-okkal.

### Attribútumok ($attributes)
Ha egy HTML attribútumot szeretnénk átadni egy komponensnek (pl. href, class, id stb.), azt egyszerűen a komponens meghívásakor adjuk meg. Például:

```html
<x-card href="/movies/{{ $movie['id'] }}" />
```

A komponens sablonjában ezt az attribútumot így érhetjük el:

```html
<a {{ $attributes }} class="btn">Adatlap</a>
```

A {{ $attributes }} automatikusan behelyettesíti az összes átadott HTML attribútumot.
Alternatív megoldásként lekérhetünk egy adott attribútumot is:

```html
<a href="{{ $attributes->get('href') }}" class="btn">Adatlap</a>
```

### Prop-ok (@props és dinamikus értékek)

A prop-ok segítségével egyedi, nem HTML attribútumokat is átadhatunk a komponenseknek. Ezeket a komponens sablonjának elején definiáljuk az @props direktívával:

```php
@props(['highlight' => false])
```

Ez azt jelenti, hogy a highlight nevű prop alapértelmezett értéke false, ha nem adunk meg semmit.

A komponens meghívásakor így adunk át dinamikus (nem sztring) értéket:

```html
<x-card :highlight="{{ $movie['imdb_score'] >= 9.0 }}" />
```

A kettőspont (:) a highlight előtt azt jelzi, hogy a kifejezést nem sztringként, hanem PHP értékként (jelen esetben boolean-ként) kell értelmezni.

Prop használata stílus (class) dinamikus kezelésére
A Laravel Blade @class direktívája segítségével a prop értékének függvényében adhatunk hozzá CSS osztályokat:

```html
<div @class(['highlight' => $highlight, 'card'])>
    <!-- Tartalom -->
</div>
```
Ebben a példában a highlight CSS osztály csak akkor kerül hozzáadásra, ha a $highlight értéke true. A card osztály mindig jelen lesz.

### Összegzés

- Attribútumok: Olyan HTML attribútumokat jelentenek, amelyeket a $attributes segítségével kezelhetünk a komponensen belül.

- Prop-ok: Egyedi, nem HTML attribútumként működő értékek, amelyeket az @props direktívával deklarálunk, és : jellel adunk át, ha nem sztringről van szó.

- @class: Hasznos direktíva, amellyel feltételesen alkalmazhatunk CSS osztályokat prop-ok vagy más feltételek alapján.

## `7. CSS és Tailwind CSS használata`

### CSS hozzáadása Laravelben
- A Laravel projekteknél a CSS fájlokat a resources/css/app.css fájlban kezeljük. Ez a fájl tartalmazza a Tailwind direktívákat, és itt helyezhetjük el saját stílusainkat is.

### Tailwind CSS integráció
- A Laravel legfrissebb verziói alapértelmezetten tartalmazzák a Tailwind CSS-t, amely teljes mértékben előre konfigurálva van a Vite eszközzel.

### Fejlesztés Vite segítségével
- A Vite gondoskodik a CSS és JavaScript állományok összeállításáról és frissítéséről.

### Fejlesztői mód indítása:

```bash
npm run dev
```

Ez elindítja a Vite szervert, amely figyeli a fájlokat, és automatikusan frissíti a böngészőt mentés után.

Build készítése éles környezethez:

```bash
npm run build
```
Ez optimalizálja az erőforrásokat és legenerálja a produkciós verziót.

### Tailwind osztályok használata
Tailwind CSS osztályokat kétféleképpen használhatunk:

Inline módon a Blade sablonfájlokban (.blade.php)

```html
<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Gomb</button>
```
Gyakran használt elemek stílusaihoz használhatjuk a @apply direktívát a app.css fájlban:

```css
.btn-primary {
  @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
}
```
Így a sablonfájlban már csak a btn-primary osztályt kell használni.

### Egyedi osztályok létrehozása
A app.css fájlban lehetőség van egyedi osztályok definiálására is Tailwind segítségével, szintén az @apply direktívával. Ez segít a kód tisztán tartásában, és elkerülhetjük a hosszú inline osztálylistákat.

## `8. Database migrations`

Laravel – Migrációk
A Laravel migrációk lehetővé teszik az adatbázis szerkezetének verziókövetését kódból. Segítségükkel létrehozhatók táblák, módosíthatók oszlopok, és definiálhatók kapcsolatok a táblák között – mindez PHP kódban.

Migráció létrehozása
Egy új migrációs fájl létrehozásához használd az artisan parancsot:

php artisan make:migrate create_todos_table
Ez létrehoz egy fájlt a database/migrations könyvtárban, amely két alapértelmezett metódust tartalmaz:

up(): Ebben határozzuk meg, mi történjen a migráció futtatásakor (például tábla létrehozása, oszlopok definiálása).

down(): Ez a metódus akkor fut le, ha visszagörgetjük (rollback) a migrációt. Ebben általában a Schema::dropIfExists('table_name') szerepel, hogy töröljük a korábban létrehozott táblát.

Migráció futtatása
A migrációkat az alábbi paranccsal lehet futtatni:

php artisan migrate
Ez végrehajtja az összes még nem futtatott migrációt.

Migráció visszavonása (rollback)
Ha vissza szeretnél vonni egy vagy több migrációt, használd:

php artisan migrate:rollback
Ez a legutóbbi migrációs „batch”-et vonja vissza (az utolsó futtatott migrációcsoportot).

Miért hasznosak a migrációk?
Verziókövetés: Nyomon követheted, hogyan változott az adatbázis szerkezete.

Csapatmunka: Több fejlesztő is dolgozhat ugyanazon projekten anélkül, hogy kézzel kellene szinkronizálni az adatbázis-változásokat.

Automatizálás: Alkalmazás telepítésénél vagy CI/CD folyamatban automatikusan futtathatók.

## `9. Eloquent models`
Migrációs fájlok – mezőnevek és adattípusok
Mezőnevek formátuma
A Laravel konvenciói szerint a migrációs fájlokban a mezőneveket snake_case formátumban érdemes megadni. Ez azt jelenti, hogy a több szóból álló nevek alulvonással ( _ ) legyenek elválasztva, például:

$table->string('task_name');
$table->text('detailed_description');
Ez nem kötelező, de a Laravel belső működése (pl. kapcsolatok, automatikus oszlopfelismerés) is ezt a formátumot részesíti előnyben, így érdemes követni.

Adattípus megválasztása
Szöveges adatok tárolásánál fontos megválasztani a megfelelő adattípust:

string – maximum 255 karakter hosszúságú szöveg tárolására alkalmas. Például címek, nevek, rövid szövegek esetén.

$table->string('title');
text – nagyobb mennyiségű szöveg tárolására ajánlott. Ilyen például egy hosszabb leírás, megjegyzés vagy cikk szövege.

$table->text('description');
Amennyiben várhatóan hosszabb tartalmat szeretnénk tárolni, célszerű a text típus használata, mivel a string típus korlátozott hosszúságú.

Eloquent modellek használata Laravelben
A Laravel Eloquent ORM (Object-Relational Mapping) lehetővé teszi, hogy az adatbázisműveleteket objektumorientált módon, egyszerűen és átláthatóan végezzük.

Modellek létrehozása és használata
Egy modell létrehozása után az adott modell az App\Models névtérben helyezkedik el. A használatához a következőképpen hivatkozhatunk rá:

use App\Models\Todo;
A Todo modell a Laravel beépített Model osztályát örökli:

class Todo extends Model
{
    //
}
Ezáltal automatikusan elérhetővé válnak az Eloquent által biztosított műveletek, például:

Todo::create([...]) – új rekord létrehozása

Todo::find($id) – rekord lekérdezése azonosító alapján

Todo::all() – összes rekord lekérdezése

Tömeges kitöltés (Mass Assignment)
Fontos biztonsági intézkedésként a modellben meg kell határozni, hogy mely mezők módosíthatók tömegesen. Ezt a $fillable vagy a $guarded tulajdonság segítségével tehetjük meg.

Példa – $fillable használata:

protected $fillable = ['title', 'description'];
Ebben az esetben csak a felsorolt mezők (pl. title, description) módosíthatók tömeges adatátadás során, például a create() vagy update() metódusok használatakor.

Alternatíva – $guarded használata:

protected $guarded = ['id'];
A $guarded tulajdonság pont az ellenkező elvet követi: itt azokat a mezőket soroljuk fel, amelyeket nem lehet tömegesen módosítani. Ha például csak az id mezőt szeretnénk védeni, akkor a fenti beállítást használjuk.

Tesztelés – Artisan Tinker
A Laravel tinker parancsa lehetőséget biztosít az alkalmazás belső működésének interaktív kipróbálására:

php artisan tinker
A tinker konzolon keresztül például létrehozhatunk új rekordot:

use App\Models\Todo;

Todo::create([
    'title' => 'Első feladat',
    'description' => 'Ez egy tesztfeladat.'
]);
A fenti példában feltételezzük, hogy a title és description mezők szerepelnek a $fillable tömbben.

## `10. Model factories`

Model Factory-k használata Laravelben
A Laravel model factory funkciója egy rendkívül hasznos eszköz, amely lehetővé teszi tesztadatok gyors és automatizált létrehozását. A factory egy ún. tervezési minta (Design Pattern), amely az objektumok előre definiált sablon alapján történő létrehozását teszi lehetővé.

Mire jó a model factory?
A Laravel factory segítségével egyszerűen hozhatunk létre nagy mennyiségű adatrekordot az adatbázisban, elsősorban:

fejlesztés közbeni tesztelésre

adatbázis seedelésére

unit és feature tesztekhez

A factory működése szorosan kapcsolódik a Faker könyvtárhoz, amely véletlenszerű, de valósághű tesztadatokat generál (pl. nevek, email címek, dátumok stb.).

Használat példa:
Ha például van egy Todo modellünk, amelyhez készült factory, akkor az alábbi parancs segítségével 50 darab teszt rekordot hozhatunk létre:

Todo::factory()->count(50)->create();
Ez az utasítás meghívható például:

php artisan tinker parancssori környezetben

Seeder osztályokon belül

A factory automatikusan generálja az adatokat a database/factories/TodoFactory.php fájlban definiált szabályok alapján.

Gyakori lépések saját factory létrehozásához
Factory létrehozása artisan paranccsal:

php artisan make:factory TodoFactory --model=Todo
Mezők definiálása a factory fájlban:

public function definition(): array
{
    return [
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'completed' => $this->faker->boolean,
    ];
}

## `11. Seeders`

Seeder osztályok használata Laravelben
A Laravel Seeder osztályok segítségével automatizálhatjuk az adatbázis tesztadatokkal való feltöltését. A seederek gyakran együtt használatosak factory-kkal, hiszen míg a factory csak adatokat generál, a seeder meghatározza mikor, milyen modellekhez és milyen mennyiségben történjen ez.

Miért hasznos a Seeder?
Amikor több táblát is kezelünk, és minden táblába szeretnénk tesztadatokat generálni, a factory-k kézi meghívása (pl. Tinkerből) időigényes és monoton lehet. A Seeder ezt a folyamatot egyszerűsíti és automatizálja.

Seeder létrehozása
Seeder osztályt az alábbi Artisan paranccsal hozhatunk létre:

php artisan make:seeder TodoSeeder
Ezután a database/seeders/TodoSeeder.php fájlban definiálhatjuk az adatgenerálást:

public function run(): void
{
    \App\Models\Todo::factory()->count(50)->create();
}
Seeder futtatása
Seeder osztály futtatása:

php artisan db:seed --class=TodoSeeder
Ha több Seeder-t szeretnél futtatni egyszerre, akkor azokat regisztrálni kell a DatabaseSeeder.php fájlban:

public function run(): void
{
    $this->call([
        TodoSeeder::class,
        UserSeeder::class,
    ]);
}
Ezután elegendő a fő seeder meghívása:

php artisan db:seed
Kombinálás migrációval
A tesztadatok és az adatbázisszerkezet együttes újragenerálásához használhatjuk a következő parancsot:

php artisan migrate:fresh --seed
Ez a parancs:

Törli az összes táblát

Újra lefuttatja az összes migrációt

Lefuttatja a DatabaseSeeder-ben regisztrált seedereket

## `13. MVC & Controllers`

Laravel – Controller (Vezérlő) áttekintés
A Laravel egy MVC (Model-View-Controller) alapú PHP keretrendszer. A Controller (vezérlő) felelős az adatok kezelése és a nézet (view) között történő kommunikáció irányításáért.

1. A Controller szerepe az MVC-ben
Model: az adatbázis réteget reprezentálja (pl. Todo modell).

View: a megjelenítésért felel.

Controller: összeköti a modellt a nézettel, és kezeli a HTTP kéréseket (pl. megjelenítés, létrehozás, szerkesztés, törlés).

2. Route és Controller kapcsolata
A routes/web.php fájlban definiálhatunk útvonalakat, melyekhez kontrollermódszereket rendelünk:

use App\Http\Controllers\TodoController;

Route::get('/todos', [TodoController::class, 'index']);
Route::get('/todos/{id}', [TodoController::class, 'show']);
Route::get('/todos/{id}/edit', [TodoController::class, 'edit']);
Route::put('/todos/{id}', [TodoController::class, 'update']);
Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
Az útvonalakhoz rendelt metódusok a TodoController osztályban találhatók.

3. Tipikus Controller metódusok (RESTful)
Laravel támogatja a RESTful konvenciókat. Egy tipikus TodoController így nézhet ki:

use App\Models\Todo;

class TodoController extends Controller
{
    public function index() {
        $todos = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function show($id) {
        $todo = Todo::findOrFail($id);
        return view('todos.show', compact('todo'));
    }

    public function edit($id) {
        $todo = Todo::findOrFail($id);
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, $id) {
        $todo = Todo::findOrFail($id);
        $todo->update($request->all());
        return redirect()->route('todos.index');
    }

    public function destroy($id) {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return redirect()->route('todos.index');
    }
}

4. Modell importálása
A vezérlőben a modell használatához az use kulcsszóval importáljuk azt a fájl tetején:

use App\Models\Todo;
Ezután már használhatjuk az Eloquent ORM műveleteket, mint például: all(), find(), create(), update(), delete().

5. Hasznos tipp
Laravelben erősen ajánlott a Route::resource() használata, amely automatikusan létrehozza a RESTful útvonalakat egy vezérlőhöz:

Route::resource('todos', TodoController::class);
Ez automatikusan kezeli az index, create, store, show, edit, update, destroy metódusokat.

## `14. Named Routes`

Named Routes a Laravelben
A named routes (elnevezett útvonalak) használata lehetővé teszi, hogy a Laravel alkalmazáson belül ne közvetlenül az URL-ekre hivatkozzunk, hanem egy előre definiált név alapján. Ez különösen nagyobb projekteknél segíti elő a kód átláthatóságát és karbantarthatóságát, mivel az útvonalak esetleges módosítása nem igényli az összes hivatkozás frissítését a nézetekben vagy vezérlőkben.

Példa:
Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
A fenti példában az /todos útvonalhoz rendeltük hozzá a todos.index nevet. Ezután az alkalmazás bármely részében erre az útvonalra hivatkozhatunk a következőképpen:

URL generálás:

route('todos.index')
Link generálás Blade sablonban:

```html
<a href="{{ route('todos.index') }}">Összes feladat</a>
```
Elnevezési konvenció:
A Laravel közösségben általánosan elfogadott konvenció szerint a route neve tükrözi az útvonal célját és az azt kezelő vezérlő (controller) logikáját. A név általában két részből áll:

Erőforrás neve (pl. todos) – gyakran egyezik az URL első szegmensével vagy a mappa nevével.

Művelet neve (pl. index, create, store, stb.) – a vezérlőben lévő metódus logikáját tükrözi.

Előnyök:
Karbantarthatóság: Ha az útvonal módosul, csak a routes/web.php fájlban kell átírni, nem az egész alkalmazásban.

Olvashatóság: A todos.index típusú hivatkozások egyértelműbbek, mint a konkrét URL-ek.

Hibák csökkentése: Kevesebb az esély az elgépelésre vagy duplikációra.

## `15. Pagination`

Oldalszámozás (Pagination) a Laravelben
A Laravel tartalmaz egy beépített oldalszámozási rendszert, amely megkönnyíti nagy mennyiségű adat kezelhetőbb megjelenítését. Az oldalszámozást az Eloquent ORM segítségével egyszerűen integrálhatjuk az adatlekérdezéseinkbe.

Alapvető használat
A vezérlőben az adatok lekérése során a paginate() metódust használjuk:

$todos = Todo::paginate(10);
Ez a lekérdezés 10 rekordot jelenít meg oldalanként, és automatikusan kezeli az aktuális oldal számát a kérés URL paraméterei alapján (pl. ?page=2).

Adatok átadása a nézetbe
A vezérlőből átadott adatok a Blade nézetben így jeleníthetők meg:

@foreach ($todos as $todo)
    <p>{{ $todo->title }}</p>
@endforeach

{{ $todos->links() }}
A links() metódus automatikusan legenerálja az oldalszámozási linkeket.

Támogatott sablonok
A Laravel többféle előre elkészített sablont támogat az oldalszámozás megjelenítéséhez. Alapértelmezetten a Tailwind CSS keretrendszert használja (Laravel 8+ verziótól). A sablont az alábbi módon lehet módosítani:

{{ $todos->links('pagination::bootstrap-5') }}
Elérhető beépített sablonok:
pagination::tailwind (alapértelmezett)

pagination::bootstrap-4

pagination::bootstrap-5

pagination::simple-bootstrap-4 (csak előző/következő linkek)

Egyedi sablon is létrehozható a resources/views/vendor/pagination mappában.

Előnyök:
Automatikus oldalkezelés: Kezeli a page paramétert és az offsetet.

Teljesen testreszabható: Sablonok, linkek stílusa, megjelenített elemek száma.

## `16. ForeignKeys`

 Idegen kulcs és kapcsolatok (Foreign key, 1:N kapcsolat)
Példád alapján:

categories tábla: kategóriák (pl. "Munka", "Otthon", "Tanulás")

todos tábla: feladatok

Egy kategóriához sok todo tartozhat – ez egy a sokhoz kapcsolat (1:N).

A todos táblában van egy category_id mező, ami idegen kulcs a categories.id-re.

Laravel Eloquent kapcsolatok
Laravel Eloquent ORM-ben a táblák közti kapcsolatokat modellekben definiálod, hogy később könnyen, olvashatóan tudj adatokat lekérni.

Category model:

class Category extends Model
{
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }
}
Ez azt mondja: "Egy kategóriának sok todo-ja lehet".

Todo model:

class Todo extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
Ez pedig: "Egy todo-hoz tartozik egy kategória".

Miért kellenek ezek a kapcsolatok?
Ezek nélkül Laravel nem tudná, hogyan kapcsolja össze a táblákat automatikusan. Ha megvannak, akkor:

Egy todo-ból eléred a kategóriáját így:

$todo = Todo::find(1);
echo $todo->category->name;
Vagy egy kategóriából az összes todo-t:

$category = Category::find(2);
foreach ($category->todos as $todo) {
    echo $todo->title;
}
Laravel automatikusan kitalálja az SQL JOIN-öket, így nem kell neked manuálisan írni őket.

with() és eager loading
Ha sok todo-t kérsz le, és minden egyeshez le akarod kérni a kategóriáját, akkor:

$todos = Todo::all();

foreach ($todos as $todo) {
    echo $todo->category->name;
}
Ez lassú lehet, mert minden category hivatkozás külön SQL lekérdezést csinál (N+1 probléma).

Ezt küszöböli ki az eager loading, azaz:

$todos = Todo::with('category')->get();
Ez azt mondja Laravelnek: „hozd le a todo-kat és a kapcsolódó kategóriákat egy SQL lekérdezésben”.

### Laravel Eloquent kapcsolat típusok
- Kapcsolat típusa	Használat
- hasOne	Egy az egyhez (1:1)
- hasMany	Egy a sokhoz (1:N)
- belongsTo	Fordított 1:N
- belongsToMany	Több a többhöz (N:N)
- hasOneThrough	Átmenő 1:1
- hasManyThrough	Átmenő 1:N
- morphOne	Polimorf 1:1
- morphMany	Polimorf 1:N
- morphTo	Polimorf fordított
- morphToMany	Polimorf N:N
