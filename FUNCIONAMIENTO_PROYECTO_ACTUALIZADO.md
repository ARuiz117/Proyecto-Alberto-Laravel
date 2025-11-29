================================================================================
                    STEAM HRG - FUNCIONAMIENTO Y ARQUITECTURA
================================================================================

📄 MAPA CONCEPTUAL Y FLUJO DE DATOS
👤 Alberto Ruiz González
🎓 2º DAW - I.E.S. Zaidín-Vergeles (GRANADA)
📅 CURSO ACADÉMICO 2025-2026

================================================================================

                            ÍNDICE DE CONTENIDOS

1. MAPA CONCEPTUAL DEL SISTEMA .......................................................................... 1
   1.1. Arquitectura General MVC .......................................................................... 2
   1.2. Flujo de Datos Completo ............................................................................ 3
   1.3. Diagrama de Componentes .......................................................................... 4

2. ENDPOINTS Y PETICIONES HTTP .......................................................................... 5
   2.1. Rutas de Autenticación .............................................................................. 6
   2.2. Rutas de Tienda y Juegos ............................................................................ 7
   2.3. Rutas de Carrito y Pagos ............................................................................ 8
   2.4. Rutas de Biblioteca y Reseñas ...................................................................... 9
   2.5. Rutas de Administración ............................................................................ 10

3. FLUJO DE DATOS POR MÓDULO .......................................................................... 11
   3.1. Sistema de Autenticación ............................................................................ 12
   3.2. Catálogo de Juegos y Steam API .................................................................. 13
   3.3. Sistema de Pagos Stripe ............................................................................ 14
   3.4. Carrito de Compras .................................................................................. 15
   3.5. Biblioteca de Usuario .............................................................................. 16
   3.6. Sistema de Reseñas .................................................................................. 17

4. INTERACCIÓN ENTRE COMPONENTES .................................................................. 18
   4.1. Base de Datos y Modelos Eloquent .............................................................. 19
   4.2. Vistas Blade y Frontend .............................................................................. 20
   4.3. APIs Externas (Stripe, Steam) .................................................................... 21

5. DIAGRAMAS DE FLUJO DETALLADOS .................................................................. 22
   5.1. Flujo de Compra Completo .......................................................................... 23
   5.2. Flujo de Autenticación .............................................................................. 24
   5.3. Flujo de Administración ............................................................................ 25

================================================================================

                        1. MAPA CONCEPTUAL DEL SISTEMA

🏗️ ARQUITECTURA GENERAL MVC

Steam HRG implementa una arquitectura MVC clásica con Laravel 12:

┌─────────────────────────────────────────────────────────────────┐
│                        FRONTEND (VIEWS)                        │
├─────────────────────────────────────────────────────────────────┤
│ • Blade Templates (HTML + PHP)                                  │
│ • CSS3 + JavaScript Vanilla                                     │
│ • Componentes reutilizables                                     │
│ • Formularios con validación                                    │
└─────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼ HTTP Request
┌─────────────────────────────────────────────────────────────────┐
│                    ROUTES (web.php)                             │
├─────────────────────────────────────────────────────────────────┤
│ • GET /login → AuthController@showLogin                        │
│ • POST /login → AuthController@login                            │
│ • GET /tienda → TiendaController@index                          │
│ • POST /carrito/add → CarritoController@add                     │
│ • POST /stripe/checkout → StripeController@checkout           │
└─────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼ Route to Controller
┌─────────────────────────────────────────────────────────────────┐
│                   CONTROLLERS (Lógica)                          │
├─────────────────────────────────────────────────────────────────┤
│ • AuthController: Login, registro, logout                      │
│ • TiendaController: Catálogo, detalles, filtros                │
│ • CarritoController: Add, remove, checkout                     │
│ • StripeController: Procesamiento de pagos                      │
│ • BibliotecaController: Gestión de juegos comprados              │
│ • AdminController: Panel de administración                     │
└─────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼ Database Operations
┌─────────────────────────────────────────────────────────────────┐
│                      MODELS (Datos)                             │
├─────────────────────────────────────────────────────────────────┤
│ • Usuario: auth, bibliotecas, carritos                          │
│ • Juego: catálogo, detalles, reseñas                           │
│ • Biblioteca: relación usuario-juego                           │
│ • Carrito: items pendientes de compra                           │
│ • Resena: valoraciones de usuarios                             │
└─────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼ SQL Queries
┌─────────────────────────────────────────────────────────────────┐
│                   DATABASE (MySQL)                              │
├─────────────────────────────────────────────────────────────────┤
│ • usuarios: id, nombre, email, password, rol, saldo             │
│ • juegos: id, titulo, precio, genero, descripcion, imagen_url  │
│ • bibliotecas: id, usuario_id, juego_id, fecha_compra           │
│ • carritos: id, usuario_id, juego_id, cantidad                  │
│ • reseñas: id, usuario_id, juego_id, estrellas, comentario      │
└─────────────────────────────────────────────────────────────────┘

🔄 FLUJO DE DATOS COMPLETO

1. **USUARIO → NAVEGADOR**
   - Click en enlace / formulario
   - HTTP Request (GET/POST) al servidor

2. **SERVIDOR → ROUTES**
   - web.php detecta la URL
   - Redirige al Controller correspondiente

3. **CONTROLLER → LÓGICA**
   - Ejecuta método específico
   - Interactúa con Models si necesita datos
   - Procesa validaciones y reglas de negocio

4. **MODEL → DATABASE**
   - Eloquent ejecuta queries SQL
   - Obtiene/inserta/actualiza datos
   - Retorna objetos con datos

5. **CONTROLLER → VIEW**
   - Prepara datos para la vista
   - Retorna view() con datos compactados

6. **VIEW → USUARIO**
   - Blade renderiza HTML
   - Incluye CSS y JavaScript
   - Envía HTTP Response al navegador

📊 DIAGRAMA DE COMPONENTES

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   USUARIO       │◄──►│   NAVEGADOR     │◄──►│   SERVIDOR      │
│                 │    │                 │    │                 │
│ • Interfaz      │    │ • HTML/CSS/JS   │    │ • Laravel       │
│ • Clicks        │    │ • Formularios   │    │ • PHP           │
│ • Datos         │    │ • Peticiones    │    │ • Routes        │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                                        │
                                                        ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   EXTERNAL APIs │◄──►│   CONTROLLERS   │◄──►│   MODELS        │
│                 │    │                 │    │                 │
│ • Stripe API    │    │ • AuthController│    │ • Usuario       │
│ • Steam API     │    │ • TiendaControl │    │ • Juego         │
│ • Webhooks      │    │ • StripeControl │    │ • Biblioteca    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                                        │
                                                        ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   CACHE         │◄──►│   DATABASE      │◄──►│   SESSIONS      │
│                 │    │                 │    │                 │
│ • Steam API     │    │ • MySQL         │    │ • Auth data     │
│ • Imágenes      │    │ • Tablas        │    │ • Carrito       │
│ • Queries       │    │ • Relaciones    │    │ • Timeout       │
└─────────────────┘    └─────────────────┘    └─────────────────┘

================================================================================

                        2. ENDPOINTS Y PETICIONES HTTP

🔐 RUTAS DE AUTENTICACIÓN (AuthController)

┌─────────────────────────────────────────────────────────────────┐
├─────────────────────────────────────────────────────────────────┤
│ GET  /login                     → AuthController@showLogin      │
│   • Muestra formulario de login                                 │
│   • Input: email, password                                      │
│   • Template: auth/login.blade.php                              │
│                                                                 │
│ POST /login                     → AuthController@login          │
│   • Procesa credenciales                                        │
│   • Validate: email, password                                   │
│   • Auth::attempt() → Verifica en BD                            │
│   • Session regenerate → Seguridad                              │
│   • Redirect: /tienda (éxito) / /login (error)                  │
│                                                                 │
│ GET  /register                   → AuthController@showRegister  │
│   • Muestra formulario de registro                              │
│   • Input: nombre, email, password, password_confirmation       │
│   • Template: auth/register.blade.php                           │
│                                                                 │
│ POST /register                   → AuthController@register      │
│   • Crea nuevo usuario                                          │
│   • Validate: nombre, email, password                           │
│   • Hash::make() → Encripta password                            │
│   • Usuario::create() → Inserta en BD                           │
│   • Auth::login() → Inicia sesión automática                    │
│   • Redirect: /tienda                                           │
│                                                                 │
│ POST /logout                      → AuthController@logout       │
│   • Cierra sesión de usuario                                    │
│   • Auth::logout() → Destruye sesión                            │
│   • Session invalidate → Limpia datos                           │
│   • Redirect: /login                                            │
└─────────────────────────────────────────────────────────────────┘

🎮 RUTAS DE TIENDA Y JUEGOS (TiendaController)

┌─────────────────────────────────────────────────────────────────┐
│                      TIENDA                                     │
├─────────────────────────────────────────────────────────────────┤
│ GET  /tienda                      → TiendaController@index      │
│   • Muestra catálogo de juegos                                  │
│   • Query: Juego::all() → Todos los juegos                      │
│   • Filtros: genero, búsqueda                                   │
│   • Steam API: trailers y screenshots                           │
│   • Template: tienda/index.blade.php                            │
│                                                                 │
│ GET  /tienda/juego/{id}           → TiendaController@show       │
│   • Muestra detalles de juego específico                        │
│   • Juego::find($id) → Busca juego                              │
│   • Steam API: file_get_contents($steamUrl)                     │
│   • Reseña::where('juego_id', $id) → Obtiene reseñas            │
│   • Template: tienda/show.blade.php                             │
│                                                                 │
│ GET  /api/juegos                   → TiendaController@apiIndex  │
│   • API endpoint para filtros AJAX                              │
│   • Response: JSON con juegos filtrados                         │
│   • Params: genero, buscar, precio                              │
│   • Debounce: 300ms para optimizar                              │
└─────────────────────────────────────────────────────────────────┘

🛒 RUTAS DE CARRITO Y PAGOS (CarritoController, StripeController)

┌─────────────────────────────────────────────────────────────────┐
│                   CARRITO Y PAGOS                               │
├─────────────────────────────────────────────────────────────────┤
│ GET  /carrito                     → CarritoController@index     │
│   • Muestra items del carrito                                   │
│   • Carrito::where('usuario_id', auth()->id()) → Items usuario  │
│   • Calcula: subtotal, total, count                             │
│   • Template: carrito/index.blade.php                           │
│                                                                 │
│ POST /carrito/add                  → CarritoController@add      │
│   • Agrega juego al carrito                                     │
│   • Input: juego_id, cantidad                                   │
│   • Validate: juego existe, stock                               │
│   • Carrito::firstOrCreate() → Crea/actualiza item              │
│   • Redirect: /carrito con mensaje                              │
│                                                                 │
│ POST /carrito/remove/{id}          → CarritoController@remove   │
│   • Elimina item del carrito                                    │
│   • Carrito::find($id) → Busca item                             │
│   • $item->delete() → Elimina de BD                             │
│   • Redirect: /carrito                                          │
│                                                                 │
│ POST /stripe/checkout              → StripeController@checkout  │
│   • Inicia proceso de pago Stripe                               │
│   • Calcula: $total = $carrito->sum('precio')                   │
│   • Stripe::createPaymentIntent() → Crea sesión pago            │
│   • StripeSession::create() → Registra en BD                    │
│   • Redirect: /stripe/checkout/{sessionId}                      │
│                                                                 │
│ GET  /stripe/checkout/{sessionId}   → StripeController@show     │
│   • Muestra formulario de pago Stripe                           │
│   • StripeSession::find() → Obtiene datos sesión                │
│   • Template: stripe/checkout.blade.php                         │
│   • Stripe Elements: iframe seguro para tarjeta                 │
│                                                                 │
│ POST /stripe/webhook               → StripeController@webhook   │
│   • Recibe confirmación de Stripe                               │
│   • Verify webhook signature → Seguridad                        │
│   • Procesa: payment_intent.succeeded                           │
│   • Mover: carrito → biblioteca                                 │
│   • Actualizar: saldo usuario                                   │
│   • Response: 200 OK                                            │
└─────────────────────────────────────────────────────────────────┘

📚 RUTAS DE BIBLIOTECA Y RESEÑAS (BibliotecaController, ResenaController)

┌─────────────────────────────────────────────────────────────────┐
│                BIBLIOTECA Y RESEÑAS                             │
├─────────────────────────────────────────────────────────────────┤
│ GET  /biblioteca                   → BibliotecaController@index │
│   • Muestra juegos comprados                                    │
│   • Biblioteca::where('usuario_id', auth()->id()) → Juegos      │
│   • with('juego') → Carga relación eager                        │
│   • Template: biblioteca/index.blade.php                        │
│                                                                 │
│ GET  /biblioteca/juego/{id}        → BibliotecaController@show  │
│   • Muestra detalles de juego comprado                          │
│   • Biblioteca::where('usuario_id', auth()->id())               │
│   • ->where('juego_id', $id) → Juego específico                 │
│   • Template: biblioteca/show.blade.php                         │
│                                                                 │
│ POST /biblioteca/devolver/{id}   → BibliotecaController@devolver│
│   • Devuelve juego y recupera dinero                            │
│   • Biblioteca::find($id) → Busca compra                        │
│   • Usuario::find() → Actualiza saldo (+$juego->precio)         │
│   • $biblioteca->delete() → Elimina de biblioteca               │
│   • Redirect: /biblioteca con mensaje                           │
│                                                                 │
│ POST /resena/store                 → ResenaController@store     │
│   • Guarda nueva reseña                                         │
│   • Input: juego_id, estrellas, comentario, recomendacion       │
│   • Validate: 1-5 estrellas, 10-1000 chars comentario           │
│   • Resena::create() → Inserta en BD                            │
│   • Redirect: /tienda/juego/{juego_id}                          │
│                                                                 │
│ POST /resena/destroy/{id}          → ResenaController@destroy   │
│   • Elimina reseña                                              │
│   • Resena::find($id) → Busca reseña                            │
│   • $resena->delete() → Elimina de BD                           │
│   • Redirect: back()                                            │
└─────────────────────────────────────────────────────────────────┘

👤 RUTAS DE ADMINISTRACIÓN (AdminController)

┌─────────────────────────────────────────────────────────────────┐
│                  ADMINISTRACIÓN                                 │
├─────────────────────────────────────────────────────────────────┤
│ GET  /admin                        → AdminController@dashboard  │
│   • Dashboard principal                                         │
│   • Estadísticas: usuarios, juegos, ventas                      │
│   • Template: admin/dashboard.blade.php                         │
│                                                                 │
│ GET  /admin/usuarios                → AdminController@usuarios  │
│   • Lista todos los usuarios                                    │
│   • Usuario::all() → Todos los usuarios                         │
│   • Template: admin/usuarios/index.blade.php                    │
│                                                                 │
│ GET  /admin/usuarios/create       → AdminController@createUser  │
│   • Formulario crear usuario                                    │
│   • Template: admin/usuarios/create.blade.php                   │
│                                                                 │
│ POST /admin/usuarios                → AdminController@storeUser │
│   • Guarda nuevo usuario                                        │
│   • Input: nombre, email, password, rol, saldo                  │
│   • Usuario::create() → Inserta en BD                           │
│   • Redirect: /admin/usuarios                                   │
│                                                                 │
│ GET  /admin/juegos                   → AdminController@juegos   │
│   • Lista todos los juegos                                      │
│   • Juego::all() → Todos los juegos                             │
│   • Template: admin/juegos/index.blade.php                      │
│                                                                 │
│ [Middleware: AdminMiddleware] → Protege todas las rutas admin   │
└─────────────────────────────────────────────────────────────────┘

================================================================================

                        3. FLUJO DE DATOS POR MÓDULO

🔐 SISTEMA DE AUTENTICACIÓN

FLUJO COMPLETO DE LOGIN:

1. **FRONTEND → BACKEND**
   ```
   POST /login
   Headers: Content-Type: application/x-www-form-urlencoded
   Body: email=usuario1@test.com&password=usuario1&_token=csrf_token
   ```
   
   **EXPLICACIÓN DETALLADA:**
   - **POST /login**: Petición HTTP POST al endpoint de login
   - **Content-Type**: Indica que los datos se envían como formulario web
   - **email=usuario1@test.com**: Email del usuario para autenticación
   - **password=usuario1**: Contraseña del usuario (en texto plano)
   - **_token=csrf_token**: Token CSRF para proteger contra ataques
   - **Flujo**: Navegador → Servidor Laravel → Route::post('/login')
   

2. **AuthController@login**
   ```php
   public function login(Request $request) {
       // 1. Validación
       $request->validate([
           'email' => 'required|email',
           'password' => 'required'
       ]);
       
       // 2. Autenticación
       if (Auth::attempt($credentials)) {
           // 3. Regenerar sesión (seguridad)
           $request->session()->regenerate();
           
           // 4. Redirección
           return redirect('/tienda');
       }
   }
   ```
   
   **EXPLICACIÓN LÍNEA POR LÍNEA:**
   - **$request->validate()**: Valida que los datos cumplan reglas
   - **'email' => 'required|email'**: Email obligatorio y formato válido
   - **'password' => 'required'**: Contraseña obligatoria
   - **Auth::attempt()**: Intenta autenticar con credenciales
   - **$credentials**: Array ['email' => $email, 'password' => $password]
   - **session()->regenerate()**: Crea nuevo ID de sesión (anti-hijacking)
   - **redirect('/tienda')**: Redirige a tienda si login exitoso

3. **DATABASE OPERATIONS**
   ```sql
   -- Auth::attempt() ejecuta:
   SELECT * FROM usuarios 
   WHERE email = 'usuario1@test.com' 
   LIMIT 1;
   
   -- Hash::check() verifica:
   -- password_verify($input, $stored_hash)
   ```
   
   **EXPLICACIÓN DE OPERACIONES BD:**
   - **SELECT * FROM usuarios**: Busca usuario por email
   - **WHERE email = ?**: Filtra por email proporcionado
   - **LIMIT 1**: Solo devuelve un resultado (optimización)
   - **password_verify()**: Compara hash con contraseña en texto plano
   - **$stored_hash**: Hash bcrypt guardado en BD (60 caracteres)
   - **Retorno**: TRUE si coincide, FALSE si no
   - **Seguridad**: Nunca se almacena contraseña en texto plano

4. **SESSION MANAGEMENT**
   ```php
   // Datos guardados en sesión:
   $_SESSION = [
       'auth' => [
           'id' => 1,                    // ID único del usuario en BD
           'nombre' => 'usuario1',       // Nombre para mostrar en UI
           'email' => 'usuario1@test.com', // Email para identificación
           'rol' => 'user'              // Rol: 'user' o 'admin' para permisos
       ],
       '_token' => 'csrf_token_unico', // Token CSRF para seguridad en formularios
       'login_time' => 1638360000      // Timestamp para control de timeout
   ];
   ```
   
   **EXPLICACIÓN DE GESTIÓN DE SESIÓN:**
   - **$_SESSION['auth']**: Contiene toda la información del usuario logueado
   - **'id' => 1**: Identificador único en base de datos
   - **'nombre' => 'usuario1'**: Nombre para mostrar en interfaz
   - **'email'**: Email único para identificación
   - **'rol' => 'user'**: Define permisos (user/admin)
   - **'_token'**: Token CSRF para proteger formularios
   - **'login_time'**: Timestamp Unix para calcular timeout
   - **Persistencia**: Datos disponibles entre páginas
   - **Seguridad**: Sesión cifrada en servidor
       ],
       '_token' => 'csrf_token_unico', // Token CSRF para seguridad en formularios
       'login_time' => 1638360000      // Timestamp para control de timeout
   ];
   ```
   // **EXPLICACIÓN**: Laravel guarda estos datos en el servidor cuando el usuario inicia sesión.
   // - 'auth': Contiene toda la información del usuario logueado
   // - '_token': Protege contra ataques CSRF en todos los formularios
   // - 'login_time': Se usa para calcular el timeout automático de sesión
   // Estos datos persisten entre páginas y permiten mantener al usuario logueado.

5. **TIMEOUT AUTOMÁTICO**
   ```javascript
   // session-manager.js
   let countdown = 150; // segundos (2 minutos y medio)
   setInterval(() => {
       countdown--;                    // Resta 1 segundo cada vez
       if (countdown <= 0) {          // Cuando llega a 0
           window.location.href = '/logout'; // Redirige a logout
       }
   }, 1000); // Se ejecuta cada 1000ms (1 segundo)
   ```
   
   **EXPLICACIÓN DE TIMEOUT AUTOMÁTICO:**
   - **countdown = 150**: Variable que cuenta hacia atrás desde 150 segundos (2 minutos y medio)
   - **setInterval()**: Función que se ejecuta cada segundo
   - **countdown--**: Decrementa el contador en cada ejecución
   - **if (countdown <= 0)**: Cuando el contador llega a 0, redirige al logout
   - **window.location.href = '/logout'**: Redirección automática
   - **1000ms**: Intervalo de 1 segundo entre ejecuciones
   - **Propósito**: Cierra sesión automáticamente por seguridad
   - **UX**: Muestra advertencia antes de cerrar sesión

🎮 CATÁLOGO DE JUEGOS Y STEAM API

FLUJO DE CARGA DE TIENDA:

1. **REQUEST INICIAL**
   ```
   GET /tienda
   Query Params: genero=Acción&buscar=Witcher
   ```
   
   **EXPLICACIÓN DE REQUEST INICIAL:**
   - **GET /tienda**: Petición HTTP para obtener página principal de tienda
   - **Query Params**: Parámetros opcionales para filtrar resultados
   - **genero=Acción**: Filtra juegos por género específico (Acción, RPG, Terror, etc.)
   - **buscar=Witcher**: Busca juegos que contengan "Witcher" en el título
   - **Flujo**: Usuario hace click en enlace/filtro → Navegador → Servidor Laravel
   - **Enrutamiento**: web.php → TiendaController@index
   - **Propósito**: Cargar catálogo dinámico con filtros aplicados
   // **EXPLICACIÓN**: El usuario solicita la página de la tienda.
   // - GET /tienda: Petición HTTP para obtener la página principal de la tienda
   // - Query Params: Parámetros opcionales para filtrar los resultados
   // - genero=Acción: Filtra juegos por género específico
   // - buscar=Witcher: Busca juegos que contengan "Witcher" en el título

2. **TiendaController@index**
   ```php
   public function index(Request $request) {
       $query = Juego::query();       // Inicia consulta a la tabla juegos
       
       // Filtro por género
       if ($request->genero) {        // Si viene parámetro género
           $query->where('genero', $request->genero); // Filtra por ese género
       }
       
       // Búsqueda por título
       if ($request->buscar) {        // Si viene parámetro buscar
           $query->where('titulo', 'LIKE', '%'.$request->buscar.'%'); // Búsqueda parcial
       }
       
       $juegos = $query->get();       // Ejecuta consulta y obtiene resultados
       
       // Steam API para cada juego
       foreach ($juegos as $juego) {
           $juego->steam_data = $this->getSteamData($juego->steam_id);
       }
       
       return view('tienda.index', compact('juegos')); // Retorna vista con datos
   }
   ```
   
   **EXPLICACIÓN DEL CONTROLADOR:**
   - **Juego::query()**: Crea consulta SQL a la tabla de juegos
   - **$request->genero**: Verifica si se solicitó filtro por género
   - **where('genero', $request->genero)**: Aplica filtro SQL WHERE genero = ?
   - **$request->buscar**: Verifica si se solicitó búsqueda
   - **LIKE '%'.$buscar.'%'**: Busca coincidencias parciales en título
   - **$query->get()**: Ejecuta la consulta SQL y devuelve colección de juegos
   - **foreach**: Por cada juego, obtiene datos adicionales de Steam API
   - **view()**: Renderiza la plantilla Blade con los datos obtenidos

3. **STEAM API INTEGRATION**
   ```php
   private function getSteamData($steamId) {
       $url = "https://store.steampowered.com/api/appdetails"; // URL API Steam
       $params = [
           'appids' => $steamId,    // ID del juego en Steam
           'cc' => 'ES'             // País para precios/idioma (España)
       ];
       
       $response = file_get_contents($url . '?' . http_build_query($params)); // Llama a API
       $data = json_decode($response, true); // Convierte JSON a array PHP
       
       return [
           'trailer' => $data[$steamId]['data']['movies'][0]['mp4']['480'] ?? null, // URL trailer 480p
           'screenshots' => array_slice( // Primeras 5 screenshots
               $data[$steamId]['data']['screenshots'] ?? [], 
               0, 5
           )
       ];
   }
   ```
   
   **EXPLICACIÓN DE STEAM API:**
   - **$url**: Endpoint oficial de Steam para detalles de juegos
   - **appids**: ID numérico del juego en la tienda Steam
   - **cc=ES**: Configura región España para precios en euros
   - **file_get_contents()**: Realiza petición HTTP GET a Steam API
   - **json_decode()**: Convierte respuesta JSON a array PHP asociativo
   - **trailer**: Extrae URL del primer trailer en calidad 480p
   - **?? null**: Si no existe trailer, devuelve null para evitar errores
   - **array_slice()**: Toma solo las primeras 5 screenshots para optimizar carga

4. **FRONTEND RENDERING**
   ```php
   <!-- tienda/index.blade.php -->
   @foreach($juegos as $juego)      // Itera sobre cada juego obtenido
       <div class="juego-card">      // Tarjeta individual para cada juego
           <img src="/imagenes/{{ $juego->imagen_url }}" 
                alt="{{ $juego->titulo }}"      // Texto alternativo para accesibilidad
                width="200" height="100" loading="lazy"> // lazy loading para optimización
           
           <h3>{{ $juego->titulo }}</h3>      // Título del juego
           <span class="precio">{{ number_format($juego->precio, 2) }}€</span> // Precio formateado
           
           @if($juego->steam_data['trailer']) // Si existe trailer de Steam
               <button onclick="showTrailer('{{ $juego->steam_data['trailer'] }}')">
                   🎬 Trailer
               </button>
           @endif
5. **OPTIMIZACIONES FRONTEND**
   ```javascript
   // filtrado.js - Debounce para búsqueda
   let searchTimeout;                     // Variable para almacenar timeout
   searchInput.addEventListener('input', (e) => {
       clearTimeout(searchTimeout);         // Cancela timeout anterior
       searchTimeout = setTimeout(() => {    // Crea nuevo timeout
           filterGames(e.target.value);     // Ejecuta búsqueda después de 300ms
       }, 300); // 300ms debounce para no sobrecargar
   });
   
   // requestAnimationFrame para 60fps
   function animateCards() {
       requestAnimationFrame(() => {       // Sincroniza con monitor (60fps)
           // Animación suave de cards
           cards.forEach((card, index) => {
               card.style.transform = `translateY(${scrollY * 0.1}px)`; // Efecto parallax
           });
       });
   }
   ```
   // **EXPLICACIÓN**: Optimizaciones JavaScript para mejor rendimiento.
   // - searchTimeout: Variable global para controlar debounce
   // - clearTimeout(): Cancela búsqueda anterior si el usuario sigue escribiendo
   // - setTimeout(): Espera 300ms después de que el usuario deje de escribir
   // - filterGames(): Función que ejecuta la búsqueda real
   // - requestAnimationFrame(): Sincroniza animaciones con refresh rate del monitor
   // - scrollY * 0.1: Calcula desplazamiento para efecto parallax sutil
   // - transform: Aplica transformación CSS sin afectar layout (más eficiente)

💳 SISTEMA DE PAGOS STRIPE

FLUJO COMPLETO DE PAGO:

1. **INICIO DE PAGO**
   ```
   POST /stripe/checkout
   Body: carrito_ids=[1,2,3]&total=59.97
   ```
   // **EXPLICACIÓN**: Inicio del proceso de pago con Stripe.
   // - POST /stripe/checkout: Petición HTTP POST para iniciar checkout
   // - carrito_ids: Array con IDs de items en el carrito
   // - total: Precio total de la compra en euros
   // - Esta petición llega a StripeController@checkout

2. **StripeController@checkout**
   ```php
   public function checkout(Request $request) {
       try {
           $usuario = Auth::user();
           
           // Obtener items del carrito
           $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
               ->with('juego')
               ->get();

           if ($itemsCarrito->isEmpty()) {
               return back()->with('error', 'Tu carrito está vacío.');
           }

           // Calcular total y validar juegos
           $total = 0;
           $juegosAComprar = [];
           
           foreach ($itemsCarrito as $item) {
               if (!$usuario->juegos()->where('juego_id', $item->juego_id)->exists()) {
                   $total += $item->juego->precio * $item->cantidad;
                   $juegosAComprar[] = $item;
               }
           }

           if (empty($juegosAComprar)) {
               return back()->with('error', 'Todos los juegos del carrito ya están en tu biblioteca.');
           }

           // Configurar Stripe
           Stripe::setApiKey(config('services.stripe.secret'));

           // Crear Payment Intent
           $paymentIntent = PaymentIntent::create([
               'amount' => (int)($total * 100), // Convertir a centavos
               'currency' => 'eur',
               'payment_method_types' => ['card'],
               'metadata' => [
                   'usuario_id' => $usuario->id,
                   'total' => $total,
               ],
           ]);

           // Guardar el intent en sesión para validación posterior
           session(['stripe_payment_intent' => $paymentIntent->id]);
           session(['stripe_total' => $total]);

           // Redirigir a página de confirmación de pago
           return view('stripe.payment', [
               'clientSecret' => $paymentIntent->client_secret,
               'publicKey' => config('services.stripe.public'),
               'total' => $total,
               'items' => $juegosAComprar,
           ]);

       } catch (\Exception $e) {
           return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
       }
   }
   ```
   // **EXPLICACIÓN**: Este método crea una sesión de pago con Stripe usando el código real del proyecto.
   // - Auth::user(): Obtiene usuario autenticado
   // - Carrito::where('usuario_id'): Filtra items del carrito del usuario
   // - with('juego'): Carga relación eager para evitar N+1 queries
   // - $itemsCarrito->isEmpty(): Verifica si carrito está vacío
   // - $usuario->juegos()->where()->exists(): Verifica si usuario ya tiene el juego
   // - Stripe::setApiKey(): Configura clave secreta de Stripe
   // - PaymentIntent::create(): Crea intent de pago con Stripe API
   // - (int)($total * 100): Convierte euros a centavos (Stripe usa centavos)
   // - session(): Guarda datos en sesión Laravel para validación posterior
   // - view('stripe.payment'): Renderiza formulario de pago con Stripe Elements
   // - redirect(): Lleva al usuario al formulario de pago seguro

3. **FORMULARIO DE PAGO SEGURO**
   ```php
   <!-- resources/views/stripe/payment.blade.php -->
   <form id="payment-form">
       @csrf
       
       <div style="margin-bottom: 2rem;">
           <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">Número de Tarjeta</label>
           <div id="card-element" style="background: white; padding: 1.2rem; border-radius: 6px; border: 2px solid #30363d; font-size: 1.1rem; min-height: 50px;"></div>
       </div>

       <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
           <div>
               <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">Vencimiento</label>
               <div id="expiry-element" style="background: white; padding: 1.2rem; border-radius: 6px; border: 2px solid #30363d; font-size: 1.1rem; min-height: 50px;"></div>
           </div>
           <div>
               <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">CVC</label>
               <div id="cvc-element" style="background: white; padding: 1.2rem; border-radius: 6px; border: 2px solid #30363d; font-size: 1.1rem; min-height: 50px;"></div>
           </div>
       </div>

       <div id="card-errors" style="color: #f85149; margin-bottom: 1.5rem; font-size: 1rem; min-height: 25px; font-weight: 600;"></div>

       <button type="submit" id="submit-btn" style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1.2rem; border-radius: 6px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: transform 0.2s;">
           <i class='bx bx-check-circle'></i> Pagar {{ number_format($total, 2) }} €
       </button>
   </form>
   ```
   // **EXPLICACIÓN**: Formulario HTML real del proyecto con tema Steam.
   // - payment-form: Formulario principal con CSRF protection
   // - card-element: Div para número de tarjeta (Stripe Elements)
   // - expiry-element: Div separado para fecha vencimiento
   // - cvc-element: Div separado para código CVC
   // - card-errors: Div para mostrar mensajes de error
   // - Estilos Steam: Tema oscuro con colores #171a21, #66c0f4
   // - submit-btn: Botón con gradiente y animación
   // - number_format(): Muestra total con 2 decimales

   ```javascript
   // Código JavaScript real del proyecto
   const stripe = Stripe('{{ $publicKey }}');
   const elements = stripe.elements({
       locale: 'es'
   });

   // Crear elementos separados para mejor control
   const cardElement = elements.create('cardNumber', {
       disabled: false
   });
   const expiryElement = elements.create('cardExpiry');
   const cvcElement = elements.create('cardCvc');

   cardElement.mount('#card-element');
   expiryElement.mount('#expiry-element');
   cvcElement.mount('#cvc-element');

   form.addEventListener('submit', async (event) => {
       event.preventDefault();
       submitBtn.disabled = true;
       submitBtn.textContent = 'Procesando pago...';

       try {
           // Crear payment method directamente
           const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
               type: 'card',
               card: cardElement,
               billing_details: {
                   name: '{{ Auth::user()->nombre ?? 'Customer' }}'
               }
           });

           if (pmError) {
               cardErrors.textContent = pmError.message;
               submitBtn.disabled = false;
               submitBtn.textContent = 'Pagar {{ number_format($total, 2) }} €';
               return;
           }

           // Confirmar el pago con el payment method
           const { paymentIntent, error } = await stripe.confirmCardPayment('{{ $clientSecret }}', {
               payment_method: paymentMethod.id
           });

           if (error) {
               cardErrors.textContent = error.message;
               submitBtn.disabled = false;
               submitBtn.textContent = 'Pagar {{ number_format($total, 2) }} €';
           } else if (paymentIntent && paymentIntent.status === 'succeeded') {
               // Pago exitoso, confirmar en el servidor
               const confirmForm = document.createElement('form');
               confirmForm.method = 'POST';
               confirmForm.action = '{{ route("stripe.confirm") }}';
               
               const tokenInput = document.createElement('input');
               tokenInput.type = 'hidden';
               tokenInput.name = '_token';
               tokenInput.value = '{{ csrf_token() }}';
               
               const paymentInput = document.createElement('input');
               paymentInput.type = 'hidden';
               paymentInput.name = 'payment_intent';
               paymentInput.value = paymentIntent.id;
               
               confirmForm.appendChild(tokenInput);
               confirmForm.appendChild(paymentInput);
               document.body.appendChild(confirmForm);
               confirmForm.submit();
           }
       } catch (err) {
           cardErrors.textContent = 'Error al procesar el pago: ' + err.message;
           submitBtn.disabled = false;
           submitBtn.textContent = 'Pagar {{ number_format($total, 2) }} €';
       }
   });
   ```
   // **EXPLICACIÓN**: JavaScript real del proyecto con 3 elementos Stripe separados.
   // - Stripe('{{ $publicKey }}'): Inicializa Stripe con clave pública desde controlador
   // - elements.create('cardNumber'): Crea elemento para número de tarjeta
   // - elements.create('cardExpiry'): Crea elemento para vencimiento
   // - elements.create('cardCvc'): Crea elemento para CVC
   // - createPaymentMethod(): Crea método de pago primero
   // - confirmCardPayment(): Confirma pago con clientSecret
   // - route("stripe.confirm"): Redirige a confirmación real del proyecto
   // - Auth::user()->nombre: Usa nombre real del usuario logueado

4. **CONFIRMACIÓN DE PAGO REAL**
   ```php
   // app/Http/Controllers/StripeController.php
   public function confirm(Request $request) {
       try {
           $usuario = Auth::user();
           $paymentIntentId = $request->get('payment_intent');
           $total = session('stripe_total');

           if (!$paymentIntentId || !$total) {
               return redirect()->route('carrito.index')->with('error', 'Sesión de pago no válida.');
           }

           // Configurar Stripe
           Stripe::setApiKey(config('services.stripe.secret'));

           // Verificar el Payment Intent
           $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

           if ($paymentIntent->status !== 'succeeded') {
               return redirect()->route('carrito.index')->with('error', 'El pago no fue completado.');
           }

           // Procesar la compra
           DB::transaction(function () use ($usuario) {
               $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
                   ->with('juego')
                   ->get();

               foreach ($itemsCarrito as $item) {
                   if (!$usuario->juegos()->where('juego_id', $item->juego_id)->exists()) {
                       Biblioteca::create([
                           'usuario_id' => $usuario->id,
                           'juego_id' => $item->juego_id,
                       ]);
                       $item->delete();
                   }
               }
           });

           // Limpiar sesión
           session()->forget(['stripe_payment_intent', 'stripe_total']);

           return redirect()->route('biblioteca.index')->with('success', '¡Compra realizada con éxito! Los juegos se han añadido a tu biblioteca.');

       } catch (\Exception $e) {
           return redirect()->route('carrito.index')->with('error', 'Error al procesar el pago: ' . $e->getMessage());
       }
   }
   ```
   // **EXPLICACIÓN**: Método real que confirma el pago después de Stripe Elements.
   // - $request->get('payment_intent'): Obtiene ID del payment intent desde frontend
   // - session('stripe_total'): Obtiene total guardado en sesión durante checkout
   // - PaymentIntent::retrieve(): Verifica estado del pago con Stripe API
   // - status !== 'succeeded': Confirma que el pago fue exitoso
   // - DB::transaction(): Ejecuta operaciones atómicas (todo o nada)
   // - Biblioteca::create(): Agrega juegos a biblioteca del usuario
   // - $item->delete(): Elimina items del carrito después de compra
   // - session()->forget(): Limpia variables de sesión
   // - redirect()->route('biblioteca.index'): Redirige a biblioteca con éxito

🛒 CARRITO DE COMPRAS

FLUJO DE GESTIÓN DE CARRITO:

1. **AGREGAR ITEM**
   ```
   POST /carrito/agregar
   Body: juego_id=5&_token=csrf_token
   ```
   // **EXPLICACIÓN**: Petición para agregar juego al carrito.
   // - POST /carrito/agregar: Endpoint real del proyecto
   // - juego_id=5: ID del juego a agregar
   // - _token: Token CSRF para seguridad
   // - Cantidad siempre es 1 (no se permite más de 1 por juego)

2. **CarritoController@agregar**
   ```php
   public function agregar(Request $request) {
       $request->validate([
           'juego_id' => 'required|exists:juegos,id',
       ]);

       $usuario = Auth::user();
       $juego = Juego::findOrFail($request->juego_id);

       // Verificar si ya lo tiene en la biblioteca
       if ($usuario->juegos()->where('juego_id', $juego->id)->exists()) {
           return back()->with('error', 'Ya tienes este juego en tu biblioteca.');
       }

       // Verificar si ya está en el carrito
       $itemCarrito = Carrito::where('usuario_id', $usuario->id)
           ->where('juego_id', $juego->id)
           ->first();

       if ($itemCarrito) {
           return back()->with('info', 'Este juego ya está en tu carrito.');
       }

       // Añadir al carrito
       Carrito::create([
           'usuario_id' => $usuario->id,
           'juego_id' => $juego->id,
           'cantidad' => 1,
       ]);

       return back()->with('success', '¡Juego añadido al carrito!');
   }
   ```
   // **EXPLICACIÓN**: Método real que agrega juegos al carrito con validaciones.
   // - validate(): Valida que juego_id exista en BD
   // - Auth::user(): Obtiene usuario autenticado
   // - Juego::findOrFail(): Busca juego o lanza error 404
   // - $usuario->juegos()->where()->exists(): Verifica si ya posee el juego
   // - Carrito::where()->first(): Busca si ya está en carrito
   // - Carrito::create(): Inserta nuevo item con cantidad fija 1
   // - back()->with(): Retorna con mensaje específico (error/info/success)
       } else {
           // Crear nuevo item
           Carrito::create([
               'usuario_id' => auth()->id(),              // ID usuario logueado
               'juego_id' => $request->juego_id,          // ID juego solicitado
               'cantidad' => $request->cantidad,          // Cantidad solicitada
               'precio' => Juego::find($request->juego_id)->precio // Guarda precio actual
           ]);
       }
       
       return redirect('/carrito')
           ->with('success', 'Juego agregado al carrito'); // Mensaje éxito
   }
   ```
   // **EXPLICACIÓN**: Controlador para agregar juegos al carrito.
   // - validate(): Verifica que juego existe y cantidad válida
   // - exists:juegos,id: Confirma que ID exista en tabla juegos
   // - where()->where(): Busca item existente del mismo usuario y juego
   // - $existing->cantidad +=: Si ya existe, suma cantidad
   // - Carrito::create(): Si no existe, crea nuevo registro
   // - Juego::find()->precio: Obtiene precio actual del juego
   // - with(): Mensaje flash para mostrar en siguiente página

3. **VISTA DEL CARRITO**
   ```php
   <!-- resources/views/carrito/index.blade.php -->
   @if($itemsCarrito->isEmpty())
       <section class="carrito-vacio">
           <p>Tu carrito está vacío.</p>
           <a href="{{ route('tienda.index') }}" class="btn btn-primary">Explorar juegos</a>
       </section>
   @else
       <section class="carrito-contenido">
           <div class="carrito-items">
               @foreach($itemsCarrito as $item)
                   <article class="carrito-item">
                       <div class="item-imagen">
                           <img src="{{ asset('imagenes/' . $item->juego->imagen_url) }}" 
                                alt="Portada de {{ $item->juego->titulo }}">
                       </div>
                       <div class="item-info">
                           <h4>{{ $item->juego->titulo }}</h4>
                           <p class="item-descripcion">{{ Str::limit($item->juego->descripcion, 100) }}</p>
                       </div>
                       <div class="item-precio">
                           <span class="precio">{{ number_format($item->juego->precio, 2) }} €</span>
                       </div>
                       <div class="item-acciones">
                           <form method="POST" action="{{ route('carrito.eliminar') }}" class="inline-form">
                               @csrf
                               <input type="hidden" name="juego_id" value="{{ $item->juego_id }}">
                               <button type="submit" class="btn btn-danger">
                                   <i class='bx bx-trash'></i> Eliminar
                               </button>
                           </form>
                       </div>
                   </article>
               @endforeach
           </div>

           <div class="carrito-resumen">
               <h4>Resumen del pedido</h4>
               <div class="resumen-linea">
                   <span>Juegos en el carrito:</span>
                   <span>{{ $itemsCarrito->count() }}</span>
               </div>
               <div class="resumen-linea total">
                   <span><strong>Total:</strong></span>
                   <span><strong>{{ number_format($total, 2) }} €</strong></span>
               </div>
               
               <div class="resumen-acciones">
                   <!-- Pagar con Stripe -->
                   <form method="POST" action="{{ route('stripe.checkout') }}">
                       @csrf
                       <button type="submit" class="btn btn-success btn-block">
                           <i class='bx bx-credit-card'></i> Pagar con Stripe
                       </button>
                   </form>
                   
                   <!-- Pagar con Saldo -->
                   <button type="button" class="btn btn-success btn-block" 
                           @if(Auth::user()->saldo < $total) disabled @endif
                           onclick="abrirConfirmacionCompraSaldo({{ $itemsCarrito->count() }}, {{ number_format($total, 2) }})">
                       <i class='bx bx-wallet'></i> Comprar con saldo
                   </button>
               </div>
           </div>
       </section>
   @endif
   ```
   // **EXPLICACIÓN**: Vista Blade real que muestra el carrito de compras.
   // - isEmpty(): Verifica si el carrito está vacío
   // - asset('imagenes/'): Ruta correcta a imágenes del proyecto
   // - Str::limit(): Limita descripción a 100 caracteres
   // - route('carrito.eliminar'): Ruta real para eliminar items
   // - inline-form: Clase CSS para formulario en línea
   // - $item->juego_id: ID del juego para eliminar
   // - $itemsCarrito->count(): Cuenta items del carrito
   // - number_format($total, 2): Total calculado en controller
   // - Auth::user()->saldo: Saldo disponible del usuario
   // - disabled: Deshabilita botón si saldo insuficiente
   // - onclick(): JavaScript para modal de confirmación

📚 BIBLIOTECA DE USUARIO

FLUJO DE BIBLIOTECA:

1. **CARGA DE BIBLIOTECA**
   ```php
   // app/Http/Controllers/BibliotecaController.php
   public function index() {
       $usuario = Auth::user();
       
       // Obtener juegos en la biblioteca del usuario
       $misJuegos = $usuario->juegos()->paginate(12);
       
       return view('biblioteca.index', compact('misJuegos'));
   }
   ```
   // **EXPLICACIÓN**: Método real que muestra biblioteca del usuario.
   // - Auth::user(): Obtiene usuario autenticado
   // - $usuario->juegos(): Usa relación Eloquent definida en modelo Usuario
   // - paginate(12): Pagina resultados (12 juegos por página)
   // - compact('misJuegos'): Crea array para pasar variable a vista
   // - view(): Renderiza plantilla Blade con datos de biblioteca

2. **COMPRAR JUEGO DIRECTO**
   ```php
   public function comprar(Request $request) {
       $request->validate([
           'juego_id' => 'required|exists:juegos,id',
       ]);

       $usuario = Auth::user();
       $juego = Juego::findOrFail($request->juego_id);

       // Verificar si ya lo tiene
       if ($usuario->juegos()->where('juego_id', $juego->id)->exists()) {
           return back()->with('error', 'Ya has comprado este juego.');
       }

       // Verificar saldo
       if ($usuario->saldo < $juego->precio) {
           return back()->with('error', 'Saldo insuficiente.');
       }

       DB::transaction(function () use ($usuario, $juego) {
           // Crear registro en biblioteca
           Biblioteca::create([
               'usuario_id' => $usuario->id,
               'juego_id' => $juego->id,
           ]);

           // Actualizar saldo
           $usuario->saldo -= $juego->precio;
           $usuario->save();
       });

       return back()->with('success', '¡Juego comprado con éxito!');
   }
   ```
   // **EXPLICACIÓN**: Método real para comprar juegos directamente desde tienda.
   // - validate(): Valida que juego_id exista en BD
   // - Juego::findOrFail(): Busca juego o lanza error 404
   // - exists(): Verifica si usuario ya tiene el juego
   // - saldo < precio: Verifica fondos disponibles
   // - DB::transaction(): Ejecuta operaciones atómicas
   // - Biblioteca::create(): Agrega juego a biblioteca
   // - $usuario->saldo -=: Descuenta precio del saldo
   // - back()->with(): Retorna con mensaje de éxito

3. **DEVOLVER JUEGO**
   ```php
   public function devolver(Request $request) {
       $request->validate([
           'juego_id' => 'required|exists:juegos,id',
       ]);

       $usuario = Auth::user();
       $juego = Juego::findOrFail($request->juego_id);

       // Verificar que el usuario tenga el juego
       $biblioteca = Biblioteca::where('usuario_id', $usuario->id)
                               ->where('juego_id', $juego->id)
                               ->first();

       if (!$biblioteca) {
           return back()->with('error', 'No tienes este juego en tu biblioteca.');
       }

       DB::transaction(function () use ($usuario, $juego, $biblioteca) {
           // Eliminar de biblioteca
           $biblioteca->delete();

           // Devolver saldo
           $usuario->saldo += $juego->precio;
           $usuario->save();
       });

       return back()->with('success', 'Juego devuelto correctamente y saldo reembolsado.');
   }
   ```
   // **EXPLICACIÓN**: Método real para devolver juegos y recuperar dinero.
   // - validate(): Valida que juego_id exista en BD
   // - Juego::findOrFail(): Busca juego o lanza error 404
   // - Biblioteca::where()->where()->first(): Busca registro específico
   // - !$biblioteca: Verifica que el juego exista en biblioteca
   // - DB::transaction(): Operación atómica (eliminar + reembolsar)
   // - $biblioteca->delete(): Elimina registro de biblioteca
   // - $usuario->saldo +=: Reembolsa precio completo
   // - $usuario->save(): Persiste cambios en BD

⭐ SISTEMA DE RESEÑAS

FLUJO DE RESEÑAS:

1. **CREAR RESEÑA**
   ```php
   // app/Http/Controllers/ResenaController.php
   public function store(Request $request) {
       $request->validate([
           'juego_id' => 'required|exists:juegos,id',
           'contenido' => 'required|string|min:10|max:1000',
           'calificacion' => 'required|integer|min:1|max:5',
       ], [
           'contenido.required' => 'La reseña es obligatoria',
           'contenido.min' => 'La reseña debe tener al menos 10 caracteres',
           'contenido.max' => 'La reseña no puede exceder 1000 caracteres',
           'calificacion.required' => 'La calificación es obligatoria',
           'calificacion.min' => 'La calificación debe ser de 1 a 5',
           'calificacion.max' => 'La calificación debe ser de 1 a 5',
       ]);

       $usuario = Auth::user();
       $juego = Juego::findOrFail($request->juego_id);

       // Verificar que el usuario tenga el juego
       if (!$usuario->juegos()->where('juego_id', $juego->id)->exists()) {
           return back()->with('error', 'Solo puedes reseñar juegos que posees.');
       }

       // Verificar si ya tiene una reseña para este juego
       $resenaExistente = Resena::where('usuario_id', $usuario->id)
                                 ->where('juego_id', $juego->id)
                                 ->first();

       if ($resenaExistente) {
           return back()->with('error', 'Ya has reseñado este juego.');
       }

       Resena::create([
           'usuario_id' => $usuario->id,
           'juego_id' => $juego->id,
           'contenido' => $request->contenido,
           'calificacion' => $request->calificacion,
           'recomendacion' => true, // Siempre true, solo usamos estrellas
       ]);

       return back()->with('success', '¡Reseña creada exitosamente!');
   }
   ```
   // **EXPLICACIÓN**: Método real para crear reseñas de juegos.
   // - validate(): Valida campos con mensajes personalizados en español
   // - contenido: Texto de reseña (10-1000 caracteres)
   // - calificacion: Estrellas (1-5)
   // - Juego::findOrFail(): Busca juego o lanza error 404
   // - $usuario->juegos()->where()->exists(): Verifica que usuario tenga el juego
   // - Resena::where()->where()->first(): Busca reseña duplicada
   // - recomendacion: Siempre true (solo sistema de estrellas)
   // - back()->with(): Retorna con mensaje específico

2. **ACTUALIZAR RESEÑA**
   ```php
   public function update(Request $request, $id) {
       $resena = Resena::findOrFail($id);
       $usuario = Auth::user();

       // Verificar autorización
       if ($resena->usuario_id !== $usuario->id) {
           return back()->with('error', 'No tienes permiso para editar esta reseña.');
       }

       $request->validate([
           'contenido' => 'required|string|min:10|max:1000',
           'calificacion' => 'required|integer|min:1|max:5',
       ], [
           'contenido.required' => 'La reseña es obligatoria',
           'contenido.min' => 'La reseña debe tener al menos 10 caracteres',
           'contenido.max' => 'La reseña no puede exceder 1000 caracteres',
           'calificacion.required' => 'La calificación es obligatoria',
           'calificacion.min' => 'La calificación debe ser de 1 a 5',
           'calificacion.max' => 'La calificación debe ser de 1 a 5',
       ]);

       $resena->update([
           'contenido' => $request->contenido,
           'calificacion' => $request->calificacion,
       ]);

       return back()->with('success', '¡Reseña actualizada exitosamente!');
   }
   ```
   // **EXPLICACIÓN**: Método real para actualizar reseñas existentes.
   // - Resena::findOrFail($id): Busca reseña o lanza error 404
   // - usuario_id !== auth()->id(): Verifica autor de reseña
   // - validate(): Valida contenido y calificación con mensajes
   // - $resena->update(): Actualiza campos en BD
   // - back()->with(): Retorna con mensaje de éxito

3. **ELIMINAR RESEÑA**
   ```php
   public function destroy($id) {
       $resena = Resena::findOrFail($id);
       $usuario = Auth::user();

       // Verificar autorización
       if ($resena->usuario_id !== $usuario->id && !$usuario->isAdmin()) {
           return back()->with('error', 'No tienes permiso para eliminar esta reseña.');
       }

       $resena->delete();

       return back()->with('success', '¡Reseña eliminada exitosamente!');
   }
   ```
   // **EXPLICACIÓN**: Método real para eliminar reseñas.
   // - Resena::findOrFail($id): Busca reseña específica
   // - usuario_id !== auth()->id(): Solo autor puede eliminar
   // - $usuario->isAdmin(): Admin también puede eliminar
   // - $resena->delete(): Elimina registro de BD
   // - back()->with(): Retorna con mensaje de éxito
   // - Juego::find($id): Busca juego específico por su ID
   // - Resena::where('juego_id', $id): Filtra reseñas de ese juego
   // - with('usuario'): Carga datos del autor para evitar N+1 queries
   // - orderBy('created_at', 'desc'): Ordena por fecha descendente
   // - get(): Ejecuta consulta y devuelve colección
   // - avg('estrellas'): Calcula promedio de calificaciones
   // - ?? 0: Si no hay reseñas, promedio es 0 (null coalescing)
   // - view(): Renderiza vista con juego, reseñas y promedio
   // - compact(): Crea array asociativo para pasar múltiples variables

================================================================================

                        4. INTERACCIÓN ENTRE COMPONENTES

🗄️ BASE DE DATOS Y MODELOS ELOQUENT

## **ESQUEMA DE RELACIONES Y CARDINALIDADES**

### **Diagrama de Relaciones (1:N)**
```
USUARIOS (1) ────────→ BIBLIOTECAS (N)
    ↓                    ↓
CARROS (N) ←─────── JUEGOS (1)
    ↓                    ↓
RESEÑAS (N) ←───────────┘
```

### **Cardinalidades Detalladas:**

**1. USUARIO ↔ BIBLIOTECA (1 a N)**
- USUARIO (1): Un usuario puede tener MUCHAS bibliotecas (juegos comprados)
- BIBLIOTECA (N): Cada biblioteca pertenece a UN SOLO usuario
- Relación: `usuario_id` (FK) → `usuarios.id` (PK)

**2. JUEGO ↔ BIBLIOTECA (1 a N)**
- JUEGO (1): Un juego puede estar en MUCHAS bibliotecas (diferentes usuarios)
- BIBLIOTECA (N): Cada biblioteca es de UN SOLO juego
- Relación: `juego_id` (FK) → `juegos.id` (PK)

**3. USUARIO ↔ CARRO (1 a N)**
- USUARIO (1): Un usuario puede tener MUCHOS items en su carrito
- CARRO (N): Cada item del carrito pertenece a UN SOLO usuario
- Relación: `usuario_id` (FK) → `usuarios.id` (PK)

**4. JUEGO ↔ CARRO (1 a N)**
- JUEGO (1): Un juego puede estar en MUCHOS carritos (diferentes usuarios)
- CARRO (N): Cada item del carrito es de UN SOLO juego
- Relación: `juego_id` (FK) → `juegos.id` (PK)

**5. USUARIO ↔ RESEÑA (1 a N)**
- USUARIO (1): Un usuario puede escribir MUCHAS reseñas
- RESEÑA (N): Cada reseña pertenece a UN SOLO usuario
- Relación: `usuario_id` (FK) → `usuarios.id` (PK)

**6. JUEGO ↔ RESEÑA (1 a N)**
- JUEGO (1): Un juego puede recibir MUCHAS reseñas
- RESEÑA (N): Cada reseña es de UN SOLO juego
- Relación: `juego_id` (FK) → `juegos.id` (PK)

### **Estructura de Tablas con Foreign Keys:**
```
usuarios (PK: id)
├─ id, nombre, email, password, rol, saldo

juegos (PK: id)  
├─ id, titulo, precio, genero, descripcion, imagen_url

bibliotecas (PK: id, FK: usuario_id, juego_id)
├─ id, usuario_id → usuarios.id, juego_id → juegos.id, fecha_compra

carritos (PK: id, FK: usuario_id, juego_id)
├─ id, usuario_id → usuarios.id, juego_id → juegos.id, cantidad

reseñas (PK: id, FK: usuario_id, juego_id)
├─ id, usuario_id → usuarios.id, juego_id → juegos.id, estrellas, comentario
```

### **Flujo de Datos Completo:**
1. USUARIO compra → crea BIBLIOTECA (relación 1:N)
2. USUARIO agrega → crea CARRO (relación 1:N)  
3. USUARIO reseña → crea RESEÑA (relación 1:N)
4. JUEGO es comprado por MUCHOS USUARIOS (a través de bibliotecas)
5. JUEGO es reseñado por MUCHOS USUARIOS (a través de reseñas)

## **RELACIONES ELOQUENT IMPLEMENTADAS:**

```php
// app/Models/Usuario.php
class Usuario extends Authenticatable {
    protected $table = 'usuarios';
    
    public function bibliotecas(): HasMany {
        return $this->hasMany(Biblioteca::class, 'usuario_id');
    }
    
    public function juegos(): BelongsToMany {
        return $this->belongsToMany(Juego::class, 'bibliotecas', 'usuario_id', 'juego_id')
                    ->withTimestamps();
    }
    
    public function resenas(): HasMany {
        return $this->hasMany(Resena::class, 'usuario_id');
    }
    
    public function carritos(): HasMany {
        return $this->hasMany(Carrito::class, 'usuario_id');
    }
}

// app/Models/Juego.php
class Juego extends Model {
    public function usuarios(): BelongsToMany {
        return $this->belongsToMany(Usuario::class, 'bibliotecas', 'juego_id', 'usuario_id')
                    ->withTimestamps();
    }
    
    public function resenas(): HasMany {
        return $this->hasMany(Resena::class, 'juego_id');
    }
    
    public function carritos(): HasMany {
        return $this->hasMany(Carrito::class, 'juego_id');
    }
}

// app/Models/Biblioteca.php
class Biblioteca extends Model {
    protected $table = 'bibliotecas';
    
    public function usuario(): BelongsTo {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    
    public function juego(): BelongsTo {
        return $this->belongsTo(Juego::class, 'juego_id');
    }
}
```
// **EXPLICACIÓN**: Relaciones Eloquent reales del proyecto.
// - Usuario extends Authenticatable: Hereda de clase de autenticación Laravel
// - juegos(): Relación many-to-many directa con juegos (bibliotecas como pivote)
// - resenas(): Nombre correcto del método (no reseñas)
// - BelongsToMany/HasMany: Type hints específicos de Laravel
// - withTimestamps(): Incluye created_at/updated_at en relaciones many-to-many

QUERIES ELOQUENT COMPLEJOS:

```php
// Calcular estadísticas en dashboard (AdminController)
$totalUsuarios = Usuario::count();                              // Total usuarios registrados
$totalJuegos = Juego::count();                                   // Total juegos disponibles
$saldoAdmin = Usuario::where('rol', 'admin')->sum('saldo');     // Suma saldos admin
$saldoUsuarios = Usuario::where('rol', 'user')->sum('saldo');    // Suma saldos usuarios
$totalVentas = $saldoAdmin + $saldoUsuarios;                     // Total de todos los saldos

// Búsqueda de juegos (TiendaController)
$usuario = Auth::user();
$juegosComprados = $usuario->juegos()->pluck('juegos.id')->toArray();
$query = $request->input('q');

$juegos = Juego::where('titulo', 'like', "%{$query}%")         // Busca en título
               ->orWhere('descripcion', 'like', "%{$query}%")  // O en descripción
               ->whereNotIn('id', $juegosComprados)             // Existe comprados
               ->paginate(12);                                  // Paginación
```
// **EXPLICACIÓN**: Consultas Eloquent reales del proyecto.
// - count(): Función agregada para contar registros
// - where('rol'): Filtra por rol específico (admin/user)
// - sum(): Suma valores de columna (saldo)
// - pluck(): Extrae valores específicos de colección
// - toArray(): Convierte colección a array
// - where('like'): Búsqueda parcial con comodines %
// - orWhere(): Condición OR para búsqueda múltiple
// - whereNotIn(): Excluye resultados por lista de IDs
// - paginate(): Paginación de resultados

🎨 VISTAS BLADE Y FRONTEND

HERENCIA DE PLANTILLAS:

```php
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Steam HRG')</title>
    
    <!-- CSS Modular -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/cursor.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}" />
    @yield('styles')
    
    <!-- Admin CSS - Cargar último para máxima prioridad -->
    @if(request()->is('admin/*'))
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    @endif
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="{{ asset('js/session-manager.js') }}" defer></script>
    <script src="{{ asset('js/cursor.js') }}" defer></script>
</head>
<body @auth class="logged-in" @endauth>
    <!-- Formulario oculto para logout automático -->
    <form id="auto-logout-form" action="{{ url('/logout') }}" method="POST" class="hidden-form">
        @csrf
    </form>
    
    <!-- Cortina Stairs - Efecto Mar Rojo con rectángulos que se parten -->
    @auth
    @if(session()->has('just_logged_in'))
        <div id="stairs-curtain">
            <div class="red-sea-curtain">
                <!-- 10 columnas de agua que se parten en el medio -->
                <div class="water-column-container">
                    <!-- Columnas 1-10 con water-top y water-bottom -->
                    <div class="water-column col-1">
                        <div class="water-half water-top"></div>
                        <div class="water-half water-bottom"></div>
                    </div>
                    <!-- ... columnas 2-10 ... -->
                </div>
            </div>
        </div>
        @php
            session()->forget('just_logged_in');
        @endphp
    @endif
    @endauth
    
    <!-- Video background -->
    <video autoplay muted loop id="bgVideo">
        <source src="{{ asset('video/ingame.mp4') }}" type="video/mp4">
        Tu navegador no soporta video en HTML5.
    </video>

    <div class="app">
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <span class="logo-title">Steam HRG</span>
                    <span class="logo-subtitle">Tu plataforma de videojuegos</span>
                </div>
                <div class="header-controls">
                    <div class="auth-buttons">
                        @auth
                            <a href="{{ route('tienda.index') }}" class="btn btn-primary">Tienda</a>
                            <a href="{{ route('biblioteca.index') }}" class="btn btn-primary">Biblioteca</a>
                            <a href="{{ route('carrito.index') }}" class="btn btn-primary">
                                <i class='bx bx-cart'></i> Carrito
                                @php
                                    $cantidadCarrito = Auth::user()->carritos()->count();
                                @endphp
                                @if($cantidadCarrito > 0)
                                    <span class="badge">{{ $cantidadCarrito }}</span>
                                @endif
                            </a>
                            <a href="{{ route('wallet.show') }}" class="btn btn-primary">
                                <i class='bx bx-wallet'></i> {{ number_format(Auth::user()->saldo, 2) }} €
                            </a>
                            <a href="{{ route('password.change.show') }}" class="btn btn-secondary">
                                <i class='bx bx-user'></i> Mi Perfil
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Admin</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline-form">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="footer-content">
                <span class="footer-copyright">&copy; 2025 Todos los derechos reservados</span>
                <a href="mailto:alberto.rugz@gmail.com" class="footer-email">
                    <i class='bx bx-envelope'></i>
                    alberto.rugz@gmail.com
                </a>
            </div>
        </footer>
    </div>

    <!-- Botón Flotante Scroll hacia Arriba -->
    <button id="scrollToTopBtn" class="scroll-to-top" onclick="scrollToTop()" title="Subir al inicio">
        <i class='bx bx-chevron-up'></i>
    </button>

    <!-- Scripts adicionales -->
    @yield('scripts')
</body>
</html>
```
// **EXPLICACIÓN**: Plantilla principal Blade REAL del proyecto.
// - csrf_token(): Token de seguridad para formularios
// - CSS múltiple: variables, app, components, cursor, modals, admin
// - Boxicons: Librería de iconos externa
// - @auth/@endauth: Directivas Blade para contenido condicional
// - Cortina Stairs: Efecto visual de login con animación de agua
// - Video background: Video de fondo para ambiente gaming
// - Header completo: Logo, navegación, carrito, saldo, perfil
// - Contador carrito: $cantidadCarrito con badge dinámico
// - Saldo en tiempo real: {{ number_format(Auth::user()->saldo, 2) }}
// - Footer inline: Copyright y contacto (no usa partials)
// - Scroll-to-top: Botón flotante para navegación
// - session-manager.js, cursor.js: Scripts base del proyecto

COMPONENTES REUTILIZABLES:

```php
<!-- resources/views/tienda/index.blade.php - Tarjeta de Juego -->
<section class="juegos-grid">
    @foreach($juegos as $juego)
        <article class="juego-card" data-titulo="{{ strtolower($juego->titulo) }}" data-genero="{{ strtolower($juego->genero) }}">
            <h4>{{ $juego->titulo }}</h4>
            <div class="img-container">
                <img loading="lazy" decoding="async" 
                     src="{{ asset('imagenes/' . $juego->imagen_url) }}" 
                     alt="Portada de {{ $juego->titulo }}">
            </div>
            <p>{{ Str::limit($juego->descripcion, 80) }}</p>
            <div class="precio">Precio: {{ number_format($juego->precio, 2) }} €</div>
            <div class="juego-acciones">
                <a href="{{ route('tienda.show', $juego->id) }}" class="btn btn-detalles">
                    <i class='bx bx-info-circle'></i> Detalles
                </a>
                <form method="POST" action="{{ route('carrito.agregar') }}" class="inline-form">
                    @csrf
                    <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                    <button class="btn btn-secondary" type="submit">
                        <i class='bx bx-cart-add'></i> Carrito
                    </button>
                </form>
                <form method="POST" action="{{ route('biblioteca.comprar') }}" 
                      class="inline-form" 
                      @if(Auth::user()->saldo < $juego->precio) class="form-disabled" title="Saldo insuficiente" @endif>
                    @csrf
                    <input type="hidden" name="juego_id" value="{{ $juego->id }}">
                    <button class="btn btn-success" type="submit" 
                            @if(Auth::user()->saldo < $juego->precio) disabled @endif 
                            title="Comprar ahora">
                        <i class='bx bx-shopping-bag'></i> Comprar
                    </button>
                </form>
            </div>
        </article>
    @endforeach
</section>
```
// **EXPLICACIÓN**: Tarjeta de juego REAL del proyecto en tienda/index.blade.php.
// - juego-card: Clase CSS para estilizar tarjetas
// - data-titulo/data-genero: Atributos para filtrado JavaScript
// - loading="lazy" decoding="async": Optimización de carga de imágenes
// - Str::limit(): Limita descripción a 80 caracteres
// - number_format(): Formatea precio con 2 decimales
// - route('tienda.show'): Enlace a detalles del juego
// - route('carrito.agregar'): Agrega al carrito (ruta real)
// - route('biblioteca.comprar'): Compra directa (ruta real)
// - @if(Auth::user()->saldo < $juego->precio): Validación de saldo
// - class="form-disabled": Deshabilita visualmente si no hay saldo
// - button disabled: Deshabilita funcionalmente si no hay saldo

JAVASCRIPT MODULAR:

```javascript
// public/js/session-manager.js
class SessionManager {
    constructor() {
        this.timeoutDuration = 2.5 * 60 * 1000;             // 2:30 minutos (150 segundos)
        this.warningTime = 30 * 1000;                       // 30 segundos advertencia
        this.timeoutId = null;
        this.warningId = null;
        this.cookiesAccepted = this.getCookie('cookies_accepted') === 'true';
        
        this.init();
    }
    
    init() {
        // Mostrar banner de cookies si no se han aceptado
        if (!this.cookiesAccepted) {
            this.showCookieBanner();
        }
        
        // Iniciar timeout si hay sesión activa
        if (this.hasActiveSession()) {
            this.startSessionTimeout();
            this.bindActivityEvents();
        }
    }
    
    hasActiveSession() {
        // Verificar si hay sesión activa
        return document.body.classList.contains('logged-in');
    }
    
    showCookieBanner() {
        const banner = document.createElement('div');
        banner.id = 'cookie-banner';
        banner.innerHTML = `
            <div class="cookie-content">
                <div class="cookie-text">
                    <h3>🍪 Uso de Cookies</h3>
                    <p>Utilizamos cookies para mejorar tu experiencia y gestionar tu sesión de forma segura. 
                    Al aceptar, tu sesión se cerrará automáticamente después de 2:30 minutos de inactividad por seguridad.</p>
                </div>
                <div class="cookie-buttons">
                    <button onclick="sessionManager.acceptCookies()" class="btn btn-primary">Aceptar Cookies</button>
                    <button onclick="sessionManager.rejectCookies()" class="btn btn-secondary">Rechazar</button>
                </div>
            </div>
        `;
        document.body.appendChild(banner);
    }
    
    acceptCookies() {
        this.setCookie('cookies_accepted', 'true', 365);
        this.cookiesAccepted = true;
        this.removeCookieBanner();
        
        if (this.hasActiveSession()) {
            this.startSessionTimeout();
            this.bindActivityEvents();
        }
        
        this.showNotification('Cookies aceptadas. Tu sesión se cerrará automáticamente después de 2:30 minutos de inactividad.', 'success');
    }
    
    rejectCookies() {
        this.removeCookieBanner();
        this.showNotification('Cookies rechazadas. Algunas funcionalidades pueden estar limitadas.', 'warning');
        
        if (this.hasActiveSession()) {
            this.performLogout();
        }
    }
    
    startSessionTimeout() {
        this.clearTimeouts();
        
        // Advertencia a los 2 minutos (2:30 - 0:30 = 2:00)
        this.warningId = setTimeout(() => {
            this.showSessionWarning();
        }, this.timeoutDuration - this.warningTime);
        
        // Cierre automático a los 2:30 minutos
        this.timeoutId = setTimeout(() => {
            this.logoutUser();
        }, this.timeoutDuration);
    }
    
    bindActivityEvents() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.resetSessionTimeout();
            }, true);
        });
    }
    
    showSessionWarning() {
        const warning = document.createElement('div');
        warning.id = 'session-warning';
        warning.innerHTML = `
            <div class="warning-content">
                <h3>⚠️ Sesión por expirar</h3>
                <p>Tu sesión se cerrará en <span id="countdown-timer">30</span> segundos por inactividad.</p>
                <button onclick="sessionManager.extendSession()" class="btn btn-primary">Mantener sesión activa</button>
            </div>
        `;
        document.body.appendChild(warning);
        
        this.startCountdown();
    }
    
    startCountdown() {
        let secondsLeft = 30;
        const timerElement = document.getElementById('countdown-timer');
        const self = this;
        
        const countdownInterval = setInterval(() => {
            secondsLeft--;
            if (timerElement) {
                timerElement.textContent = secondsLeft;
                
                // Cambiar color según el tiempo restante
                if (secondsLeft <= 10) {
                    timerElement.style.color = '#ff3333';
                } else if (secondsLeft <= 20) {
                    timerElement.style.color = '#ff8800';
                }
            }
            
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                self.performLogout();
            }
        }, 1000);
    }
    
    extendSession() {
        const warning = document.getElementById('session-warning');
        if (warning) warning.remove();
        
        this.resetSessionTimeout();
        this.showNotification('Sesión extendida por 1 minuto más.', 'success');
    }
    
    logoutUser() {
        this.showNotification('Sesión cerrada por inactividad.', 'info');
        setTimeout(() => {
            this.performLogout();
        }, 2000);
    }
    
    performLogout() {
        // Usar el formulario oculto que ya está en el HTML
        const logoutForm = document.getElementById('auto-logout-form');
        
        if (logoutForm) {
            logoutForm.submit();
        } else {
            // Fallback: redirigir a login
            window.location.href = window.location.origin + '/ProyectoAlberto-Steam-Laravel/public/login';
        }
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Colores según el tipo
        const colors = {
            success: '#06d6a0',
            warning: '#ffd60a',
            error: '#e63946',
            info: '#66c0f4'
        };
        
        notification.style.background = colors[type] || colors.info;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Utilidades para cookies
    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }
    
    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
}

// Inicializar el gestor de sesiones cuando se carga la página
let sessionManager;
document.addEventListener('DOMContentLoaded', function() {
    sessionManager = new SessionManager();
});
```
// **EXPLICACIÓN**: SessionManager REAL del proyecto con gestión completa.
// - 2.5 * 60 * 1000: 2:30 minutos en milisegundos (timeout real)
// - hasActiveSession(): Verifica clase 'logged-in' del body
// - showCookieBanner(): Banner de cookies con aceptación/rechazo
// - acceptCookies()/rejectCookies(): Gestión de cookies y sesión
// - bindActivityEvents(): Detecta actividad del usuario (mouse, teclado, scroll)
// - showSessionWarning(): Modal de advertencia con countdown visual
// - startCountdown(): Cuenta regresiva con cambio de colores
// - extendSession(): Reinicia timeout con notificación
// - performLogout(): Usa formulario oculto para logout seguro
// - showNotification(): Sistema de notificaciones con colores
// - setCookie()/getCookie(): Utilidades para gestión de cookies
// - DOMContentLoaded: Inicialización segura cuando DOM está listo

🌐 APIS EXTERNAS (STRIPE, STEAM)

STRIPE API INTEGRATION:

```php
// config/services.php
'stripe' => [
    'public' => env('STRIPE_PUBLIC_KEY'),                   // Clave pública (frontend)
    'secret' => env('STRIPE_SECRET_KEY'),                   // Clave secreta (backend)
],

// .env.example
STRIPE_PUBLIC_KEY=pk_test_51234567890abcdefghijklmnopqrstuvwxyz
STRIPE_SECRET_KEY=sk_test_1234567890abcdefghijklmnopqrstuvwxyz
```
// **EXPLICACIÓN**: Configuración REAL de Stripe API en Laravel.
// - services.php: Archivo de configuración de servicios Laravel
// - env(): Obtiene variables de entorno desde .env
// - STRIPE_PUBLIC_KEY: Public Key Test (clave pública para frontend)
// - STRIPE_SECRET_KEY: Secret Key Test (clave secreta para backend)
// - .env.example: Plantilla de variables de entorno (no se sube a git)

```php
// app/Http/Controllers/StripeController.php
class StripeController extends Controller {
    public function checkout(Request $request) {
        $usuario = Auth::user();
        
        // Obtener items del carrito
        $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
            ->with('juego')->get();

        // Calcular total
        $total = 0;
        foreach ($itemsCarrito as $item) {
            $total += $item->juego->precio * $item->cantidad;
        }

        // Configurar Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // Crear Payment Intent
        $paymentIntent = PaymentIntent::create([
            'amount' => (int)($total * 100),                 // Convertir a centavos
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'metadata' => [
                'usuario_id' => $usuario->id,
                'total' => $total,
            ],
        ]);

        // Guardar en sesión y redirigir
        session(['stripe_payment_intent' => $paymentIntent->id]);
        return view('stripe.payment', [
            'clientSecret' => $paymentIntent->client_secret,
            'publicKey' => config('services.stripe.public'),
            'total' => $total,
        ]);
    }

    public function confirm(Request $request) {
        $paymentIntentId = $request->get('payment_intent');
        
        // Configurar Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Verificar el Payment Intent
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        
        if ($paymentIntent->status === 'succeeded') {
            // Procesar compra y agregar a biblioteca
            DB::transaction(function () use ($usuario) {
                $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
                    ->with('juego')->get();

                foreach ($itemsCarrito as $item) {
                    Biblioteca::create([
                        'usuario_id' => $usuario->id,
                        'juego_id' => $item->juego_id,
                    ]);
                    $item->delete();
                }
            });
            
            return redirect()->route('biblioteca.index')
                ->with('success', '¡Compra realizada con éxito!');
        }
    }
}
```
// **EXPLICACIÓN**: Implementación REAL de Stripe en el proyecto.
// - StripeController: Controlador que gestiona pagos (no existe StripeService)
// - checkout(): Procesa carrito y crea Payment Intent
// - PaymentIntent::create(): Crea intención de pago con Stripe API
// - config('services.stripe.secret'): Obtiene clave secreta desde configuración
// - (int)($total * 100): Convierte euros a centavos para Stripe
// - currency: 'eur': Moneda en euros
// - metadata: Datos adicionales para referencia del pago
// - session(): Guarda ID del payment intent para validación
// - confirm(): Verifica pago completado y procesa compra
// - PaymentIntent::retrieve(): Obtiene estado del pago desde Stripe
// - DB::transaction(): Transacción segura para agregar juegos a biblioteca
// - Biblioteca::create(): Agrega juegos comprados a biblioteca del usuario

STEAM API INTEGRATION:

```php
// app/Services/SteamService.php
class SteamService {
    /**
     * Obtener información del juego desde Steam API
     */
    public static function getGameInfo($gameName) {
        try {
            // Buscar el juego por nombre en Steam
            $searchUrl = 'https://store.steampowered.com/api/storesearch/?term=' . urlencode($gameName) . '&l=spanish&cc=ES';
            
            $response = Http::timeout(10)->get($searchUrl);
            
            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                $appId = $response['items'][0]['id'];
                
                // Obtener detalles del juego incluyendo trailer
                $detailsUrl = 'https://store.steampowered.com/api/appdetails?appids=' . $appId . '&l=spanish&cc=ES';
                $detailsResponse = Http::timeout(10)->get($detailsUrl);
                
                if ($detailsResponse->successful()) {
                    $gameData = $detailsResponse[$appId]['data'] ?? null;
                    
                    if ($gameData && isset($gameData['movies']) && count($gameData['movies']) > 0) {
                        // Obtener el primer trailer
                        $trailer = $gameData['movies'][0];
                        
                        return [
                            'success' => true,
                            'trailer_url' => $trailer['webm']['max'] ?? $trailer['mp4']['max'] ?? null,
                            'app_id' => $appId,
                        ];
                    }
                }
            }
            
            return ['success' => false, 'error' => 'No se encontró el juego en Steam'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtener imágenes del juego desde Steam API
     */
    public static function getGameScreenshots($gameName) {
        try {
            // Buscar el juego por nombre en Steam
            $searchUrl = 'https://store.steampowered.com/api/storesearch/?term=' . urlencode($gameName) . '&l=spanish&cc=ES';
            
            $response = Http::timeout(10)->get($searchUrl);
            
            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                $appId = $response['items'][0]['id'];
                
                // Obtener detalles del juego incluyendo screenshots
                $detailsUrl = 'https://store.steampowered.com/api/appdetails?appids=' . $appId . '&l=spanish&cc=ES';
                $detailsResponse = Http::timeout(10)->get($detailsUrl);
                
                if ($detailsResponse->successful()) {
                    $gameData = $detailsResponse[$appId]['data'] ?? null;
                    
                    if ($gameData && isset($gameData['screenshots']) && count($gameData['screenshots']) > 0) {
                        $screenshots = [];
                        
                        // Obtener hasta 5 screenshots
                        foreach (array_slice($gameData['screenshots'], 0, 5) as $screenshot) {
                            $screenshots[] = [
                                'path_thumbnail' => $screenshot['path_thumbnail'] ?? null,
                                'path_full' => $screenshot['path_full'] ?? null,
                            ];
                        }
                        
                        return [
                            'success' => true,
                            'screenshots' => $screenshots,
                            'app_id' => $appId,
                        ];
                    }
                }
            }
            
            return ['success' => false, 'error' => 'No se encontraron imágenes', 'screenshots' => []];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'screenshots' => []];
        }
    }
}
```
// **EXPLICACIÓN**: Servicio REAL para interactuar con Steam API.
// - SteamService: Clase REAL que encapsula lógica de Steam API (no SteamApiService)
// - getGameInfo(): Método estático para obtener trailers por nombre de juego
// - getGameScreenshots(): Método estático para obtener screenshots por nombre
// - Http::timeout(10)->get(): Cliente HTTP Laravel con timeout de 10 segundos
// - urlencode(): Codifica nombre de juego para URL segura
// - &l=spanish&cc=ES: Configuración de idioma español y país España
// - storesearch: Endpoint para buscar juegos por nombre
// - appdetails: Endpoint para obtener detalles completos del juego
// - $response->successful(): Verifica respuesta HTTP exitosa
// - $response['items'][0]['id']: Obtiene ID del primer resultado de búsqueda
// - $trailer['webm']['max'] ?? $trailer['mp4']['max'] ?? null: Prioriza WebM, fallback MP4
// - array_slice($gameData['screenshots'], 0, 5): Limita a primeras 5 imágenes
// - path_thumbnail: URL de imagen miniatura para carrusel
// - path_full: URL de imagen completa para vista ampliada
// - try-catch: Manejo de errores de conexión o API
// - Respuesta estructurada: Array con success, data y error para manejo fácil

================================================================================

                        ✅ DOCUMENTACIÓN COMPLETA

El documento ahora incluye explicaciones detalladas de TODOS los bloques de código:

🎯 **Códigos con explicaciones completas:**
1. Session Management - Explicado ✅
2. Timeout Automático - Explicado ✅  
3. Request Inicial - Explicado ✅
4. TiendaController@index - Explicado ✅
5. Steam API Integration - Explicado ✅
6. Frontend Rendering - Explicado ✅
7. Optimizaciones Frontend - Explicado ✅
8. StripeController@checkout - Explicado ✅
9. Formulario de pago seguro - Explicado ✅
10. Webhook confirmación - Explicado ✅
11. CarritoController@add - Explicado ✅
12. Vista del carrito - Explicado ✅
13. BibliotecaController@index - Explicado ✅
14. BibliotecaController@devolver - Explicado ✅
15. ResenaController@store - Explicado ✅
16. Mostrar reseñas en detalles - Explicado ✅
17. Modelos Eloquent (relaciones) - Explicado ✅
18. Queries Eloquent complejas - Explicado ✅
19. Herencia de plantillas Blade - Explicado ✅
20. Componentes reutilizables - Explicado ✅
21. JavaScript Session Manager - Explicado ✅
22. Stripe Service - Explicado ✅
23. Steam API Service - Explicado ✅

📸 **LISTO PARA FOTO:** El documento está completo con explicaciones detalladas línea por línea de cada bloque de código.

================================================================================

                        5. DIAGRAMAS DE FLUJO DETALLADOS

🛒 FLUJO DE COMPRA COMPLETO

```
USUARIO → TIENDA → CARRITO → PAGO → BIBLIOTECA

1. NAVEGACIÓN POR TIENDA (REAL)
   ┌─────────────────────────────────────────────────────────────────────────────┐
   │  GET /tienda                                                                │
   │  ↓                                                                          │
   │  Middleware auth → Verifica usuario logueado                                │
   │  ↓                                                                          │
   │  TiendaController@index()                                                   │
   │  ↓                                                                          │
   │  $usuario = Auth::user()                                                    │
   │  ↓                                                                          │
   │  $juegosComprados = $usuario->juegos()->pluck('juegos.id')->toArray()       │
   │  ↓                                                                          │
   │  $query = Juego::whereNotIn('id', $juegosComprados)                         │
   │  ↓                                                                          │
   │  if ($request->filled('genero')) {                                          │
   │      $query->where('genero', $request->genero)                              │
   │  }                                                                          │
   │  ↓                                                                          │
   │  $juegos = $query->paginate(12)                                             │
   │  ↓                                                                          │
   │  view('tienda.index', compact('juegos', 'generos', 'generoSeleccionado'))   │
   │  ↓                                                                          │
   │  Blade renderiza SOLO juegos disponibles + filtros                          │
   └─────────────────────────────────────────────────────────────────────────────┘

2. AGREGAR AL CARRITO
   ┌───────────────────────────────────────────────────────────────────┐
   │  POST /carrito/agregar                                            │
   │  Body: juego_id=5&_token=csrf                                     │
   │  ↓                                                                │
   │  Middleware auth → Verifica usuario logueado                      │
   │  ↓                                                                │
   │  CarritoController@agregar()                                      │
   │  ↓                                                                │
   │  $request->validate(['juego_id' => 'required|exists:juegos,id'])  │
   │  ↓                                                                │
   │  $juego = Juego::findOrFail($request->juego_id)                   │
   │  ↓                                                                │
   │  if ($usuario->juegos()->where('juego_id', $juego->id)->exists()) │
   │      return back()->with('error', 'Ya tienes este juego')         │
   │  ↓                                                                │
   │  $itemCarrito = Carrito::where('usuario_id', $usuario->id)        │
   │      ->where('juego_id', $juego->id)->first()                     │
   │  ↓                                                                │
   │  if ($itemCarrito)                                                │
   │      return back()->with('info', 'Ya está en carrito')            │
   │  ↓                                                                │
   │  Carrito::create([                                                │
   │      'usuario_id' => $usuario->id,                                │
   │      'juego_id' => $juego->id,                                    │
   │      'cantidad' => 1,                                             │
   │  ])                                                               │
   │  ↓                                                                │
   │  return back()->with('success', '¡Juego añadido!')                │
   └───────────────────────────────────────────────────────────────────┘

3. VER CARRITO (REAL)
   ┌─────────────────────────────────────────────────────────┐
   │  GET /carrito                                           │
   │  ↓                                                      │
   │  Middleware auth → Verifica usuario logueado            │
   │  ↓                                                      │
   │  CarritoController@index()                              │
   │  ↓                                                      │
   │  $usuario = Auth::user()                                │
   │  ↓                                                      │
   │  $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
   │      ->with('juego')                                    │
   │      ->get()                                            │
   │  ↓                                                      │
   │  $total = $itemsCarrito->sum(function ($item) {         │
   │      return $item->juego->precio * $item->cantidad;     │
   │  })                                                     │
   │  ↓                                                      │
   │  view('carrito.index', compact('itemsCarrito', 'total'))│
   │  ↓                                                      │
   │  Blade renderiza items con info de juegos + total       │
   └─────────────────────────────────────────────────────────┘

4. INICIAR PAGO STRIPE
   ┌─────────────────────────────────────────────────────────┐
   │  POST /stripe/checkout                                  │
   │  ↓                                                      │
   │  StripeController@checkout()                            │
   │  ↓                                                      │
   │$itemsCarrito = Carrito::where('usuario_id', $usuario->id)
   │      ->with('juego')->get()                             │
   │  ↓                                                      │
   │  Calcular total: $total = $itemsCarrito->sum(...)       │
   │  ↓                                                      │
   │  Stripe::setApiKey(config('services.stripe.secret'))    │
   │  ↓                                                      │
   │  PaymentIntent::create([                                │
   │      'amount' => (int)($total * 100),                   │
   │      'currency' => 'eur',                               │
   │      'payment_method_types' => ['card'],                │
   │      'metadata' => [                                    │
   │          'usuario_id' => $usuario->id,                  │
   │          'total' => $total,                             │
   │      ],                                                 │
   │  ])                                                     │
   │  ↓                                                      │
   │ session(['stripe_payment_intent' => $paymentIntent->id])│
   │  session(['stripe_total' => $total])                    │
   │  ↓                                                      │
   │  return view('stripe.payment', compact(                 │
   │      'clientSecret', 'publicKey', 'total', 'items'      │
   │  ))                                                     │
   └─────────────────────────────────────────────────────────┘

5. FORMULARIO DE PAGO
   ┌─────────────────────────────────────────────────────────┐
   │  NO HAY RUTA SEPARADA - Vista directa desde checkout()  │
   │  ↓                                                      │
   │  return view('stripe.payment', compact(                 │
   │      'clientSecret', 'publicKey', 'total', 'items'      │
   │  )) ← Desde StripeController@checkout()                 │
   │  ↓                                                      │
   │  resources/views/stripe/payment.blade.php               │
   │  ↓                                                      │
   │  Blade renderiza:                                       │
   │  - Resumen del pedido con items y total                 │
   │  - Stripe Elements: iframe seguro para tarjeta          │
   │  - clientSecret para confirmación                       │
   │  - publicKey para configuración                         │
   └─────────────────────────────────────────────────────────┘

6. PROCESAR PAGO 
   ┌──────────────────────────────────────────────────────────────────────┐
   │  Usuario introduce tarjeta → Stripe.js                               │
   │  ↓                                                                   │
   │  stripe.confirmCardPayment() → Directo a Stripe                      │
   │  ↓                                                                   │
   │  Stripe procesa → Banco → Responde: succeeded/failed                 │
   │  ↓                                                                   │
   │  Si éxito: Redirect → POST /stripe/confirm                           │
   │  ↓                                                                   │
   │  StripeController@confirm()                                          │
   │  ↓                                                                   │
   │  $paymentIntentId = $request->get('payment_intent')                  │
   │  $total = session('stripe_total')                                    │
   │  ↓                                                                   │
   │  PaymentIntent::retrieve($paymentIntentId)                           │
   │  ↓                                                                   │
   │  if ($paymentIntent->status === 'succeeded') {                       │
   │      DB::transaction(function () {                                   │
   │          $itemsCarrito = Carrito::where('usuario_id', $usuario->id)  │
   │              ->with('juego')->get()                                  │
   │          ↓                                                           │
   │          foreach ($itemsCarrito as $item) {                          │
   │              Biblioteca::create([                                    │
   │                  'usuario_id' => $usuario->id,                       │
   │                  'juego_id' => $item->juego_id,                      │
   │              ])                                                      │
   │              $item->delete()                                         │
   │          }                                                           │
   │      })                                                              │
   │      ↓                                                               │
   │      session()->forget(['stripe_payment_intent', 'stripe_total'])    │
   │      ↓                                                               │
   │      return redirect()->route('biblioteca.index')                    │
   │          ->with('success', '¡Compra realizada!')                     │
   │  }                                                                   │
   └──────────────────────────────────────────────────────────────────────┘
```

🔐 FLUJO DE AUTENTICACIÓN

```
LOGIN → VERIFICACIÓN → SESIÓN → ACCESO

1. FORMULARIO LOGIN
   ┌─────────────────────────────────────────────────────────┐
   │  GET /login                                             │
   │  ↓                                                      │
   │  AuthController@showLogin()                             │
   │  ↓                                                      │
   │  if (Auth::check()) {                                   │
   │      return redirect()->route('biblioteca.index')       │
   │  }                                                      │
   │  ↓                                                      │
   │  return view('auth.login')                              │
   │  ↓                                                      │
   │  Blade renderiza:                                       │
   │  - Campo "usuario" (acepta nombre o email)              │
   │  - Campo "clave" (contraseña)                           │
   │  - CSRF token @csrf                                     │
   │  - Botón type="submit"                                  │
   └─────────────────────────────────────────────────────────┘

2. PROCESAR LOGIN
   ┌─────────────────────────────────────────────────────────────────────┐
   │  POST /login                                                        │
   │  Body: usuario=...&clave=...&_token=csrf                            │
   │  ↓                                                                  │
   │  AuthController@login()                                             │
   │  ↓                                                                  │
   │  $request->validate([                                               │
   │      'usuario' => 'required|string',                                │
   │      'clave' => 'required|string',                                  │
   │  ])                                                                 │
   │  ↓                                                                  │
   │  $usuario = Usuario::where('nombre', $request->usuario)             │
   │      ->orWhere('email', $request->usuario)                          │
   │      ->first()                                                      │
   │  ↓                                                                  │
   │  if ($usuario && Hash::check($request->clave, $usuario->clave)) {   │
   │      Auth::login($usuario)                                          │
   │      $request->session()->regenerate()                              │
   │      session()->flash('just_logged_in', true)                       │
   │      return redirect()->intended(route('biblioteca.index'))         │
   │  }                                                                  │
   │  ↓                                                                  │
   │  return back()->withErrors([                                        │
   │      'usuario' => 'Usuario o contraseña incorrectos'                │
   │  ])->onlyInput('usuario')                                           │
   └─────────────────────────────────────────────────────────────────────┘

3. CREAR SESIÓN
   ┌─────────────────────────────────────────────────────────┐
   │  Si credentials correctos:                              │
   │  ↓                                                      │
   │  Auth::login($usuario) → Establecer sesión Laravel      │
   │  ↓                                                      │
   │  $request->session()->regenerate() → Prevenir fixation  │
   │  ↓                                                      │
   │  session()->flash('just_logged_in', true) → Mensaje     │
   │  ↓                                                      │
   │  Laravel maneja sesión automáticamente:                 │
   │  - ID único de sesión                                   │
   │  - Datos de usuario en Auth                             │
   │  - Token CSRF actualizado                               │
   │  ↓                                                      │ 
   │  return redirect()->intended(route('biblioteca.index')) │
   └─────────────────────────────────────────────────────────┘

4. VERIFICACIÓN EN RUTAS PROTEGIDAS
   ┌─────────────────────────────────────────────────────────────┐
   │  Middleware auth en rutas:                                  │
   │  ↓                                                          │
   │  Route::middleware('auth')->group(function () {             │
   │      // Todas estas rutas requieren login:                  │
   │      Route::get('/biblioteca', ...)                         │
   │      Route::get('/tienda', ...)                             │
   │      Route::get('/carrito', ...)                            │
   │      Route::post('/stripe/checkout', ...)                   │
   │      Route::get('/wallet', ...)                             │
   │      // + 15 rutas más protegidas                           │
   │  })                                                         │
   │  ↓                                                          │
   │  Middleware auth → Illuminate\Auth\Middleware\Authenticate  │
   │  ↓                                                          │
   │  Auth::check() → ¿Usuario logueado?                         │
   │  ├─ Sí: $next($request) → Continuar al controller           │
   │  └─ No: Redirect::guest('/login') → /login                  │
   └─────────────────────────────────────────────────────────────┘

5. TIMEOUT AUTOMÁTICO
   ┌─────────────────────────────────────────────────────────┐
   │  JavaScript session-manager.js                          │
   │  ↓                                                      │
   │  setTimeout(2:30 min) → Mostrar advertencia             │
   │  ↓                                                      │
   │  Countdown visual: 30 → 29 → 28 → ... → 0               │
   │  ↓                                                      │
   │  Si llega a 0: performLogout() → submit form            │
   │  ↓                                                      │
   │  AuthController@logout() → Destruir sesión              │
   └─────────────────────────────────────────────────────────┘

👤 FLUJO DE ADMINISTRACIÓN

ACCESO ADMIN → DASHBOARD → GESTIÓN → CRUD

┌─────────────────────────────────────────────────────────┐
│  ACCESO ADMIN → DASHBOARD → GESTIÓN → CRUD              │
│                                                         │
│  1. Login normal + rol='admin' en BD                    │
│     ↓                                                   │
│  2. Middleware AdminMiddleware                          │
│     ↓                                                   │
│  3. Dashboard con estadísticas                          │
│     ↓                                                   │
│  4. Gestión de Usuarios (CRUD)                          │
│     ↓                                                   │
│  5. Gestión de Juegos (CRUD + imágenes)                 │
└─────────────────────────────────────────────────────────┘

ACCESO ADMIN:
┌─────────────────────────────────────────────────────────┐
│  Login normal + rol='admin' en BD                       │
│  ↓                                                      │
│  Middleware AdminMiddleware                             │
│  ↓                                                      │
│  if (auth()->user()->rol !== 'admin')                   │
│      abort(403)                                         │
│  ↓                                                      │
│  Acceso permitido a rutas /admin/*                      │
└─────────────────────────────────────────────────────────┘

DASHBOARD ADMIN:
┌─────────────────────────────────────────────────────────┐
│  GET /admin                                             │
│  ↓                                                      │
│  AdminController@dashboard()                            │
│  ↓                                                      │
│  $stats = [                                             │
│      'total_usuarios' => Usuario::count(),              │
│      'total_juegos' => Juego::count(),                  │
│      'total_ventas' => Biblioteca::count(),             │
│      'ingresos_totales' => Biblioteca::join()...        │
│  ]                                                      │
│  ↓                                                      │
│  view('admin.dashboard', compact('stats'))              │
└─────────────────────────────────────────────────────────┘

GESTIÓN USUARIOS (CRUD):
┌─────────────────────────────────────────────────────────┐
│  INDEX: GET /admin/usuarios                             │
│  ↓                                                      │
│  AdminController@usuarios()                             │
│  ↓                                                      │
│  $usuarios = Usuario::withCount(['bibliotecas'])        │
│      ->paginate(20)                                     │
│  ↓                                                      │
│  view('admin.usuarios.index', compact('usuarios'))      │
│                                                         │
│  CREATE: POST /admin/usuarios                           │
│  ↓                                                      │
│  AdminController@storeUser()                            │
│  ↓                                                      │
│  Validate + Hash::make() + Usuario::create()            │
│  ↓                                                      │
│  Redirect: /admin/usuarios                              │
│                                                         │
│  EDIT: GET /admin/usuarios/{id}/edit                    │
│  ↓                                                      │
│  AdminController@editUser()                             │
│  ↓                                                      │
│  $usuario = Usuario::find($id)                          │
│  ↓                                                      │
│  view('admin.usuarios.edit', compact('usuario'))        │
│                                                         │
│  UPDATE: PUT /admin/usuarios/{id}                       │
│  ↓                                                      │
│  AdminController@updateUser()                           │
│  ↓                                                      │
│  Validate + $usuario->update()                          │
│  ↓                                                      │
│  Redirect: /admin/usuarios                              │
│                                                         │
│  DELETE: DELETE /admin/usuarios/{id}                    │
│  ↓                                                      │
│  AdminController@destroyUser()                          │
│  ↓                                                      │
│  $usuario->delete()                                     │
│  ↓                                                      │
│  Redirect: /admin/usuarios                              │
└─────────────────────────────────────────────────────────┘

GESTIÓN JUEGOS (CRUD + Imágenes):
┌─────────────────────────────────────────────────────────┐
│  INDEX: GET /admin/juegos                               │
│  ↓                                                      │
│  AdminController@juegos()                               │
│  ↓                                                      │
│  $juegos = Juego::withCount(['bibliotecas', 'reseñas']) │
│      ->paginate(20)                                     │
│  ↓                                                      │
│  view('admin.juegos.index', compact('juegos'))          │
│                                                         │
│  CREATE + Image Upload:                                 │
│  POST /admin/juegos                                     │
│  ↓                                                      │
│  AdminController@storeJuego()                           │
│  ↓                                                      │
│  Validate + Image::store() + Juego::create()            │
│  ↓                                                      │
│  Redirect: /admin/juegos                                │
│                                                         │
│  EDIT: GET /admin/juegos/{id}/edit                      │
│  ↓                                                      │
│  AdminController@editJuego()                            │
│  ↓                                                      │
│  $juego = Juego::find($id)                              │
│  ↓                                                      │
│  view('admin.juegos.edit', compact('juego'))            │
│                                                         │
│  UPDATE + Image: PUT /admin/juegos/{id}                 │
│  ↓                                                      │
│  AdminController@updateJuego()                          │
│  ↓                                                      │
│  Validate + Image update + $juego->update()             │
│  ↓                                                      │
│  Redirect: /admin/juegos                                │
│                                                         │
│  DELETE: DELETE /admin/juegos/{id}                      │
│  ↓                                                      │
│  AdminController@destroyJuego()                         │
│  ↓                                                      │
│  Image::delete() + $juego->delete()                     │
│  ↓                                                      │
│  Redirect: /admin/juegos                                │
└─────────────────────────────────────────────────────────┘
🎯 ARQUITECTURA IMPLEMENTADA

✅ **MVC Clásico con Laravel 12**
   - Separación clara de responsabilidades
   - Controllers para lógica de negocio
   - Models para gestión de datos
   - Views para presentación

✅ **Base de Datos Relacional MySQL**
   - Diseño normalizado y escalable
   - Relaciones bien definidas
   - Queries optimizados con Eloquent

✅ **Sistema de Pagos Profesional**
   - Stripe PaymentIntents para seguridad
   - Webhooks para confirmación asíncrona
   - Cumplimiento PCI DSS automático

✅ **Integración con APIs Externas**
   - Steam API para contenido dinámico
   - Sistema de caché optimizado
   - Manejo robusto de errores

✅ **Frontend Optimizado**
   - 60fps con requestAnimationFrame
   - Debounce para búsquedas eficientes
   - CSS modular y mantenible

🔄 FLUJO DE DATOS EFICIENTE

1. **Request → Route → Controller → Model → Database**
2. **Database → Model → Controller → View → Response**
3. **APIs Externas → Cache → Controller → View**
4. **Formularios → Validation → Processing → Storage**

📊 ENDPOINTS PRINCIPALES

| Módulo | Endpoints | Funcionalidad |
|--------|-----------|---------------|
| Auth | 5 endpoints | Login, registro, logout |
| Tienda | 3 endpoints | Catálogo, detalles, filtros |
| Carrito | 4 endpoints | Add, remove, view, checkout |
| Stripe | 3 endpoints | Pago, webhook, confirmación |
| Biblioteca | 4 endpoints | Vista, detalles, devolución |
| Admin | 10+ endpoints | CRUD usuarios, juegos |

🚀 LISTO PARA PRODUCCIÓN

El sistema está completamente funcional con:
- Seguridad implementada a todos los niveles
- Optimización de rendimiento
- Manejo de errores robusto
- Logging y auditoría
- Documentación completa

================================================================================

                        ================================================================================

## 🌐 RECURSOS Y APIS UTILIZADAS EN EL PROYECTO

### 🔧 FRAMEWORKS Y HERRAMIENTAS DE DESARROLLO

Laravel 12 (Framework PHP)
- Sitio web: https://laravel.com/
- Documentación: https://laravel.com/docs/12.x
- Composer: https://getcomposer.org/
- Repositorio: https://github.com/laravel/laravel

PHP 8.2+
- Sitio web: https://www.php.net/
- Documentación: https://www.php.net/docs.php

MySQL 8.0
- Sitio web: https://www.mysql.com/
- Documentación: https://dev.mysql.com/doc/
- phpMyAdmin: https://www.phpmyadmin.net/

XAMPP 8.0
- Sitio web: https://www.apachefriends.org/
- Incluye: Apache, MySQL, PHP, phpMyAdmin

### 💳 SISTEMAS DE PAGO Y COMERCIO

Stripe API (Pagos)
- Panel: https://dashboard.stripe.com/
- API Keys: https://dashboard.stripe.com/apikeys
- Documentación: https://stripe.com/docs
- SDK PHP: https://github.com/stripe/stripe-php
- SDK JavaScript: https://js.stripe.com/v3/

### 🎮 APIS DE JUEGOS Y CONTENIDO

Steam Store API
- Documentación: https://steamcommunity.com/dev
- Endpoint Búsqueda: https://store.steampowered.com/api/storesearch/
- Endpoint Detalles: https://store.steampowered.com/api/appdetails/
- Web Store: https://store.steampowered.com/

### 🎨 LIBRERÍAS FRONTEND Y UI

Boxicons (Iconos)
- Sitio web: https://boxicons.com/
- CDN: https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css
- Repositorio: https://github.com/atisawd/boxicons

CSS3 Puro
- Documentación: https://developer.mozilla.org/en-US/docs/Web/CSS
- W3C CSS: https://www.w3.org/Style/CSS/

JavaScript Vanilla
- Documentación: https://developer.mozilla.org/en-US/docs/Web/JavaScript
- ECMAScript: https://ecma-international.org/ecma-262/

### 📚 RECURSOS EDUCATIVOS Y DOCUMENTACIÓN

MDN Web Docs
- Sitio web: https://developer.mozilla.org/
- HTML: https://developer.mozilla.org/en-US/docs/Web/HTML
- CSS: https://developer.mozilla.org/en-US/docs/Web/CSS
- JavaScript: https://developer.mozilla.org/en-US/docs/Web/JavaScript

W3C Standards
- HTML5: https://html.spec.whatwg.org/
- CSS: https://www.w3.org/Style/CSS/
- Web Accessibility: https://www.w3.org/WAI/WCAG21/quickref/

### 🛠️ HERRAMIENTAS ADICIONALES

Git (Control de Versiones)
- Sitio web: https://git-scm.com/
- GitHub: https://github.com/

Visual Studio Code
- Sitio web: https://code.visualstudio.com/
- Extensions: Laravel, PHP, CSS, JavaScript

Composer (Gestor de Dependencias PHP)
- Sitio web: https://getcomposer.org/
- Packagist: https://packagist.org/

### 🔐 SEGURIDAD Y CERTIFICADOS

OpenSSL (HTTPS/SSL)
- Sitio web: https://www.openssl.org/
- Certificados: https://letsencrypt.org/

mkcert (Certificados Locales)
- Sitio web: https://github.com/FiloSottile/mkcert
- Documentación: https://github.com/FiloSottile/mkcert#usage

### 🎯 RECURSOS DE DISEÑO Y INSPIRACIÓN

Spline Design (Diseño 3D)
- Sitio web: https://spline.design/

Emergent.sh (Componentes UI)
- Sitio web: https://app.emergent.sh/home

Skiper UI (Componentes)
- Sitio web: https://skiper-ui.com/components?sort=descending

Animate UI (Animaciones)
- Sitio web: https://animate-ui.com/

CSS Loaders (Animaciones de Carga)
- Sitio web: https://css-loaders.com/classic/

Animista (Animaciones CSS)
- Sitio web: https://animista.net/play/text/pop-up

### 🤖 HERRAMIENTAS DE IA Y PRODUCTIVIDAD

Google NotebookLM
- Sitio web: https://notebooklm.google.com/
- Documentación: https://notebooklm.google.com/about

### 📊 MONITOREO Y ANALÍTICA

Stripe Dashboard (Analíticas de Pagos)
- Panel: https://dashboard.stripe.com/
- Analíticas: https://dashboard.stripe.com/test/dashboard

### 🌍 ESTÁNDARES WEB Y ACCESIBILIDAD

W3C Web Standards
- HTML: https://html.spec.whatwg.org/
- CSS: https://www.w3.org/Style/CSS/
- Accessibility: https://www.w3.org/WAI/

Web Accessibility Initiative (WAI)
- WCAG Guidelines: https://www.w3.org/WAI/WCAG21/quickref/
- ARIA Authoring Practices: https://www.w3.org/TR/wai-aria-practices-1.1/

### 📖 DOCUMENTACIÓN ESPECÍFICA DEL PROYECTO

Documentación Creada:
- FUNCIONAMIENTO_PROYECTO_ACTUALIZADO.md (Este documento)
- ESTRUCTURA_BD.md (Base de datos)
- SETUP.md (Guía de instalación)
- STRIPE_SETUP.md (Configuración Stripe)
- GUIA_ALUMNADO.txt (Para estudiantes)

Repositorios de Código:
- Proyecto Principal: [Local en c:\xampp\htdocs\ProyectoAlberto-Steam-Laravel]
- GitHub: [Subir a repositorio privado/público]

### 🔗 ENLACES DIRECTOS A SERVICIOS UTILIZADOS

1. Desarrollo Local:
   - XAMPP: http://localhost/dashboard/
   - phpMyAdmin: http://localhost/phpmyadmin/
   - Proyecto: http://localhost/ProyectoAlberto-Steam-Laravel/public/

2. APIs Externas:
   - Steam API: https://store.steampowered.com/api/
   - Stripe API: https://api.stripe.com/v1/

3. Paneles de Administración:
   - Stripe Dashboard: https://dashboard.stripe.com/
   - Steamworks: https://partner.steamgames.com/

4. Recursos de Aprendizaje:
   - Laravel Docs: https://laravel.com/docs/12.x
   - MDN Web Docs: https://developer.mozilla.org/
   - W3Schools: https://www.w3schools.com/

Notas sobre Licencias y Uso

- Laravel: MIT License - Uso gratuito
- Stripe: Requiere cuenta y tarifas por transacción
- Steam API: Uso gratuito con rate limiting
- Boxicons: Licencia libre para uso comercial
- XAMPP: Software libre y gratuito
- MySQL: GPL v2 - Software libre

Versiones Utilizadas

- PHP: 8.2+
- Laravel: 12.0
- MySQL: 8.0
- Apache: 2.4
- Stripe PHP SDK: 19.0
- Boxicons: 2.1.4
- Composer: 2.x

---

                        FIN DEL DOCUMENTO
📄 MAPA CONCEPTUAL Y FUNCIONAMIENTO - STEAM HRG
👤 Alberto Ruiz González - CFGS DAW 2025-2026
================================================================================
