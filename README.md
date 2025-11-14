<div align="center">
  <table>
    <tr>
      <td align="center">
        <a href="https://laravel.com" target="_blank">
          <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
        </a>
      </td>
      <td align="center">
        <a href="https://vuejs.org" target="_blank">
          <img src="https://raw.githubusercontent.com/vuejs/art/master/logo.svg" width="100" alt="Vue.js Logo">
        </a>
      </td>
    </tr>
  </table>
</div>

# B2B/B2C Gym Payments

Proyecto base para administración de gimnasios.

## Stack tecnológico

| Capa     | Detalle                         |
|----------|---------------------------------|
| Backend  | Laravel 12, PHP 8.2+, MySQL 8   |
| Frontend | Vue 3 (SFC) + Vite 7 + pnpm 10  |
| Estilos  | Tailwind CSS v4                 |

## Requisitos previos

- PHP 8.2 o superior.
- Composer 2.6+.
- Node 20+ y pnpm 10.
- Servidor MySQL 8 (local o Docker) con un schema vacío.

## Instalación rápida

1. **Instalar dependencias PHP**

   ```bash
   composer install
   ```

2. **Instalar dependencias JS**

   ```bash
   pnpm install
   ```

3. **Configurar entorno**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Crear base MySQL y migrar**

   ```bash
   php artisan migrate
   ```

## Flujo de desarrollo

Ejecuta ambos servicios en terminales separadas:

```bash
php artisan serve
pnpm run dev
```

## Backend: Sanctum + Roles

- Instala dependencias: `composer require laravel/sanctum spatie/laravel-permission`.
- Publica assets y migra: `php artisan migrate` (se crean tablas de usuarios, tokens y roles/permisos).
- Seed inicial: `php artisan db:seed --class=RolesSeeder` genera los roles `super_admin` y `gym_admin` y un usuario `admin@example.com / password123`.
- Endpoints expuestos en `routes/api.php`:
  - `POST /api/register` crea usuarios y entrega token Sanctum.
  - `POST /api/login` autentica y devuelve token.
  - `POST /api/logout` revoca el token actual (requiere `auth:sanctum`).
  - `GET /api/user` retorna el usuario autenticado (requiere `auth:sanctum`).
  - `GET /api/dashboard` protegido por middleware `role:gym_admin|super_admin`.
  - `GET /api/admin/roles` y `POST /api/admin/users/{user}/role` permiten administrar roles/usuarios (misma protección de roles).
- Lógica de negocio (creación de usuarios, emisión/revocación de tokens y chequeo de roles) vive en `App\Models\User`.
- Configura `.env` con `SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1`, `SESSION_DOMAIN=localhost` y `APP_URL=http://localhost:8000` para que Sanctum acepte la SPA.

## Frontend: SPA con Vue 3 + Pinia + TS

- Instala dependencias JS (Vue Router, Pinia, TypeScript, axios) con `pnpm install`.
- Entrada principal: `resources/js/app.ts` monta Pinia + Router sobre `App.vue`, que solo expone `<RouterView />`.
- Router (`resources/js/router/index.ts`) define `/`, `/login`, `/register`, `/dashboard` y protege rutas según el estado de `authStore`.
- Estado global (`resources/js/stores/authStore.ts`) conserva `user` y `token` en `localStorage`, configura el header `Authorization` y ofrece acciones `login`, `register`, `logout` y `bootstrap`.
- Componentes/Vistas:
  - `components/Navbar.vue` muestra acciones según autenticación.
  - `views/Home.vue` es la landing page.
  - `views/Login.vue` y `views/Register.vue` consumen los endpoints de Laravel.
  - `views/Dashboard.vue` consulta `/api/dashboard` y muestra métricas condicionadas al rol.
  - `views/AdminRoles.vue` consume `/api/admin/*` para listar roles/permisos y reasignar roles (solo super/gym admin).

## Conectar el Login de Vue con Laravel

1. **CSRF y cookies**: no es necesario llamar a `/sanctum/csrf-cookie` porque usamos tokens personales de Sanctum. Solo asegúrate de que `axios` tenga `withCredentials = true` (ya se configura en `bootstrap.js`).
2. **Acción Login (`authStore.login`)**:
   ```ts
   await axios.post('/api/login', { email, password });
   // Respuesta -> { user, token }
   ```
   El store guarda el token (`localStorage`) y lo asigna en `axios.defaults.headers.common.Authorization`.
3. **Enlace con la UI**:
   - Formularios llaman a `auth.login` o `auth.register` y luego redirigen con `router.push`.
   - El guard del router (`beforeEach`) ejecuta `auth.bootstrap()` al recargar la página para recuperar al usuario vía `GET /api/user`.
4. **Logout**:
   ```ts
   await axios.post('/api/logout');
   auth.logout(); // borra token + estado
   ```
5. **Acceso al Dashboard**: el backend valida el rol con middleware `role:gym_admin|super_admin` y el frontend verifica roles vía `auth.hasRole('gym_admin')`.

> Recomendado: mantener sincronizados los valores `VITE_API_URL` (Vite) y `APP_URL` (Laravel) para evitar problemas de CORS.
