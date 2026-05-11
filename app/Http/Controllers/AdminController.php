<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use App\Models\GamePost;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Muestra el panel principal del administrador con estadísticas globales.
     * Recoge totales de usuarios, juegos y posts, además de los nuevos
     * registros de los últimos 30 días.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Totales globales de la plataforma
        $totalUsers = User::count();
        $totalGames = Game::count();
        $totalPosts = GamePost::count();

        // Nuevos registros en los últimos 30 días para ver tendencia de crecimiento
        $newUsersThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();
        $newGamesThisMonth = Game::where('created_at', '>=', now()->subDays(30))->count();

        return view('admin.index', compact(
            'totalUsers', 'totalGames', 'totalPosts',
            'newUsersThisMonth', 'newGamesThisMonth'
        ));
    }

    /**
     * Lista todos los usuarios con búsqueda por nombre o email y ordenación
     * por columna. Paginado en bloques de 20 resultados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        // Columna y dirección de ordenación — valores por defecto si no vienen en la request
        $sortBy  = $request->sort      ?? 'id';
        $sortDir = $request->direction ?? 'asc';

        // Whitelist de columnas permitidas para evitar SQL injection via parámetro sort
        $allowedSorts = ['id', 'name', 'email', 'role', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $users = User::when($request->search, function ($query) use ($request) {
                    // Búsqueda parcial por nombre o email
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%');
                })
                ->orderBy($sortBy, $sortDir)
                ->paginate(20);

        return view('admin.users', compact('users', 'sortBy', 'sortDir'));
    }

    /**
     * Muestra el formulario de edición de un usuario concreto.
     * Lanza 404 automáticamente si el usuario no existe.
     *
     * @param  int  $user_id
     * @return \Illuminate\View\View
     */
    public function editUser($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Valida y guarda los cambios editados sobre un usuario.
     * El email debe ser único excepto para el propio usuario que se está editando.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            // unique ignora el registro del propio usuario para no fallar al no cambiar el email
            'email' => ['required', 'email', 'unique:users,email,' . $user_id],
            'role'  => ['required', 'in:user,developer,admin'],
        ]);

        // Solo se actualizan los campos permitidos, nunca la contraseña desde aquí
        $user->update($request->only('name', 'email', 'role'));

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }

    /**
     * Alterna el estado de baneo de un usuario (ban/unban).
     * Los administradores no pueden ser baneados.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function banUser($user_id)
    {
        $user = User::findOrFail($user_id);

        // Protección: no se puede banear a otro administrador
        if ($user->role === 'admin') {
            return redirect('/admin/users');
        }

        // Toggle: si estaba baneado lo desbanea, y viceversa
        $user->update(['is_banned' => !$user->is_banned]);

        return redirect('/admin/users');
    }

    /**
     * Elimina permanentemente un usuario de la plataforma.
     * Los administradores no pueden ser eliminados desde este panel.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser($user_id)
    {
        $user = User::findOrFail($user_id);

        // Protección: no se puede eliminar a un administrador
        if ($user->role === 'admin') {
            return redirect('/admin/users');
        }

        $user->delete();
        return redirect('/admin/users');
    }

    /**
     * Lista todos los juegos con búsqueda por título y ordenación por columna.
     * Carga las relaciones user y genres para mostrarlas en la tabla sin N+1.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function games(Request $request)
    {
        $sortBy  = $request->sort      ?? 'id';
        $sortDir = $request->direction ?? 'asc';

        // Whitelist de columnas para ordenación segura
        $allowedSorts = ['id', 'title', 'status', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $games = Game::with(['user', 'genres'])
            ->when($request->search, function ($query) use ($request) {
                // Búsqueda parcial por título del juego
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(20);

        return view('admin.games', compact('games', 'sortBy', 'sortDir'));
    }

    /**
     * Elimina permanentemente un juego de la plataforma.
     *
     * @param  int  $game_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyGame($game_id)
    {
        $game = Game::findOrFail($game_id);
        $game->delete();
        return redirect('/admin/games');
    }

    /**
     * Lista todos los posts del devlog con búsqueda por título y ordenación.
     * Carga las relaciones game y user para evitar consultas N+1.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function posts(Request $request)
    {
        $sortBy  = $request->sort      ?? 'id';
        $sortDir = $request->direction ?? 'asc';

        // Whitelist de columnas para ordenación segura
        $allowedSorts = ['id', 'title', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $posts = GamePost::with(['game', 'user'])
            ->when($request->search, function ($query) use ($request) {
                // Búsqueda parcial por título del post
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(20);

        return view('admin.posts', compact('posts', 'sortBy', 'sortDir'));
    }

    /**
     * Elimina permanentemente un post del devlog.
     *
     * @param  int  $post_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPost($post_id)
    {
        $post = GamePost::findOrFail($post_id);
        $post->delete();
        return redirect('/admin/posts');
    }

    /**
     * Genera y muestra en el navegador un informe PDF con las estadísticas
     * globales de la plataforma y los rankings de juegos y desarrolladores.
     * Utiliza el paquete barryvdh/laravel-dompdf para renderizar la vista
     * como PDF y devolverla con stream() para abrirla en una nueva pestaña.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function report()
    {
        // Estadísticas generales — las mismas que en el panel principal
        $totalUsers         = User::count();
        $totalGames         = Game::count();
        $totalPosts         = GamePost::count();
        $newUsersThisMonth  = User::where('created_at', '>=', now()->subDays(30))->count();
        $newGamesThisMonth  = Game::where('created_at', '>=', now()->subDays(30))->count();

        // Top 5 juegos por número de seguidores
        $topGames = Game::withCount('follows')
            ->orderBy('follows_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 developers por número de seguidores
        $topDevelopers = User::where('role', 'developer')
            ->orWhere('role', 'admin')
            ->withCount('follows')
            ->orderBy('follows_count', 'desc')
            ->take(5)
            ->get();

        // Carga la vista Blade como PDF y la devuelve como stream (abre en pestaña nueva)
        $pdf = Pdf::loadView('admin.report', compact(
            'totalUsers', 'totalGames', 'totalPosts',
            'newUsersThisMonth', 'newGamesThisMonth',
            'topGames', 'topDevelopers'
        ));

        return $pdf->stream('indiegameconnect-report.pdf');
    }

    /**
 * Exporta la base de datos completa en formato SQL y la devuelve como descarga.
 * Utiliza mysqldump para generar el volcado directamente en el response HTTP
 * sin guardar ningún archivo en el servidor.
 * Nota: requiere que mysqldump esté disponible en el servidor.
 * En Railway puede no estar disponible dependiendo del contenedor.
 *
 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
 */
/**
 * Exporta la base de datos completa en formato SQL usando PDO puro.
 * No depende de mysqldump, funciona en cualquier servidor.
 * Genera el volcado tabla por tabla con CREATE TABLE e INSERT INTO.
 *
 * @return \Illuminate\Http\Response
 */
public function exportDatabase()
{
    // Conexión PDO directa desde la configuración de Laravel
    $host     = config('database.connections.mysql.host');
    $port     = config('database.connections.mysql.port');
    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');

    $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $sql = "-- IndieGameConnect Database Export\n";
    $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    // Obtiene todas las tablas de la BD
    $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

    foreach ($tables as $table) {

        // DROP + CREATE TABLE
        $createStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(\PDO::FETCH_ASSOC);
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $createStmt['Create Table'] . ";\n\n";

        // Filas de datos — INSERT INTO
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(\PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            $columns = array_keys($rows[0]);
            $columnList = '`' . implode('`, `', $columns) . '`';

            $sql .= "INSERT INTO `$table` ($columnList) VALUES\n";

            $values = [];
            foreach ($rows as $row) {
                $escaped = array_map(function ($val) use ($pdo) {
                    // Escapa nulls y strings correctamente
                    return is_null($val) ? 'NULL' : $pdo->quote($val);
                }, array_values($row));
                $values[] = '(' . implode(', ', $escaped) . ')';
            }

            $sql .= implode(",\n", $values) . ";\n\n";
        }
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

    // Devuelve el SQL como descarga directa
    return response($sql)
        ->header('Content-Type', 'application/sql')
        ->header('Content-Disposition', 'attachment; filename="backup_' . date('Y-m-d_H-i-s') . '.sql"');
}

/**
 * Importa un fichero SQL a la base de datos.
 * Utiliza el cliente mysql para ejecutar el volcado directamente
 * desde el fichero subido sin guardar nada permanente en el servidor.
 * Nota: requiere que mysql esté disponible en el servidor.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function importDatabase(Request $request)
{
    // Valida que el fichero sea obligatorio y tenga extensión sql o txt
    $request->validate(['sql_file' => 'required|file|mimes:sql,txt']);

    // Ruta temporal del fichero subido en el servidor
    $file = $request->file('sql_file')->getPathname();

    // Credenciales de la BD obtenidas desde la configuración de Laravel
    $db   = config('database.connections.mysql.database');
    $user = config('database.connections.mysql.username');
    $pass = config('database.connections.mysql.password');
    $host = config('database.connections.mysql.host');

    // Ejecuta el cliente mysql con el fichero SQL como entrada estándar
    shell_exec("mysql -h $host -u $user -p$pass $db < $file");

    return back()->with('success', 'Base de datos importada correctamente.');
}
}