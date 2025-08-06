<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Category;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Visszaadja a még nem elvégzett feladatokat.
     * 
     * Lekéri az összes olyan feladatot és a hozzá tartozó kategóriát, 
     * amelynek az 'is_completed' mezője false.
     * Az eredményt a 'priority' és a 'created_at' mező szerint csökkenő sorrendbe rendezi,
     * majd a 'priority' mező szerint csoportosítja őket.
     * 
     * HTTP metódus: GET
     * 
     * @return \Illuminate\View\View A feladat kártyákat megjelenítő nézet.
     */
    public function index()
    {
        $todos = Todo::select('id', 'created_at', 'category_id', 'title', 'is_completed', 'priority', 'task')
            ->with('category')
            ->where('is_completed', false)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('priority');
        return view('todos.tasklist', ["todos" => $todos]);
    }

    /**
     * Visszaadja az elvégzett feladatokat.
     * 
     * Lekéri az összes olyan feladatot, amelynek az 'is_completed' mezője true.
     * Az eredményt az 'updated_at' mező szerint csökkenő sorrendbe rendezi,
     * majd továbbítja a 'todos.completed' nézethez.
     *
     * HTTP metódus: GET
     * 
     * @return \Illuminate\View\View A befejezett teendőket megjelenítő nézet.
     */
    public function completed()
    {
        // $todos = Todo::select('id', 'title', 'is_completed', 'created_at', 'updated_at', 'task')->where('is_completed', true)->orderBy('updated_at', 'desc')->get();
        $todos = Todo::select('id', 'title', 'is_completed', 'created_at', 'updated_at', 'task')->where('is_completed', true)->orderBy('updated_at', 'desc')->paginate(5);
        return view('todos.completed', ["todos" => $todos]);
    }

    /**
     * Megjeleníti az új feladat hozzáadása űrlapot.
     * 
     * Lekéri az összes kategóriát majd átadja a nézetnek.
     * 
     * HTTP metódus: GET
     * 
     * @return \Illuminate\View\View Új feladat hozzáadása űrlap nézet.
     */
    public function add()
    {
        $categories = Category::all();
        return view('todos.add', ['categories' => $categories]);
    }

    /**
     * Megjeleníti a feladat szerkesztése űrlapot (megegyezik a hozzáadás űrlappal).
     *
     * HTTP metódus: GET
     * 
     * @param Todo $todo A szerkesztendő feladat modell példánya.
     * @return \Illuminate\View\View  Feladat szerkesztése űrlap nézet.
     */
    public function edit(Todo $todo)
    {
        $categories = Category::all();
        return view('todos.edit', compact('todo', 'categories'));
    }

    /**
     * Frissíti a megadott feladatot a validált adatok alapján.
     * 
     * A StoreTodoRequest segítségével validálja a bejövő adatokat, majd
     * kitölti (fill) a feladat modell attribútumait a validált adatokkal.
     * Amennyiben a modell módosult (isDirty), elmenti a változásokat.
     * 
     * HTTP metódus: PATCH
     * 
     * @param \App\Http\Requests\StoreTodoRequest $request A validált kérést tartalmazza.
     * @param \App\Models\Todo $todo A frissítendő feladat modell.
     * @return \Illuminate\Http\RedirectResponse Átirányítás a feladatok listájára, sikeres vagy sikertelen frissítés esetén.
     */
    public function update(StoreTodoRequest $request, Todo $todo) {
        $validate = $request->validated();
        if($todo->fill($validate)->isDirty()) {
            // $todo->update($validate); // Az update metódus a rövidített formája a fill() és save() hívásoknak. Az isDirty csak fill() után és save() előtt használható.
            $todo->save();
            return redirect()->route('todos.index')->with('success', __('labels.flashmsg.update-task'));
        }
        return redirect()->route('todos.index');
    }

    /**
     * Elmenti az új feladatot az adatbázisba
     * 
     * HTTP metódus: POST
     * 
     *@param \App\Http\Requests\StoreTodoRequest $request A validált kérést tartalmazza.
     *@return \Illuminate\Http\RedirectResponse Átirányítás a feladatok listájára mentés után.
     */
    public function store(StoreTodoRequest $request)
    {
        $validate = $request->validated();
        Todo::create($validate);
        return redirect()->route('todos.index');
    }

    /**
     * A feladat állapotának frissítése
     * 
     * HTTP metódus: PATCH
     * 
     *@param \App\Models\Todo $todo A frissítendő feladat modell.
     *@return \Illuminate\Http\RedirectResponse Átirányítás a feladatok listájára.
     */
    public function check(Todo $todo)
    {
       $todo->is_completed = true;
       $todo->save();
       return redirect()->route('todos.index')->with('success', __('labels.flashmsg.done-task'));
    }

    /**
     * Feladat törlése.
     * 
     * HTTP metódus: DELETE
     * 
     * @param \App\Models\Todo $todo A törlendő feladat modell.
     * @return \Illuminate\Http\RedirectResponse Átirányítás a feladatok listájára.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', __('labels.flashmsg.delete-task'));
    }

    /**
     * Lokalizáció beállítása
     * 
     * HTTP metódus: GET
     * 
     * @return \Illuminate\Http\RedirectResponse Visszairányítás
     */
    public function setLocale($localization)
    {
        $languages = ['hu', 'en'];

        if (in_array($localization, $languages))
            session(['locale' => $localization]);
        
        return redirect()->back();
    }
}
