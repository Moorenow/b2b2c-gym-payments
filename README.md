<p>
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" height="96" />
  <span style="margin: 0 16px; font-size: 48px; font-weight: 600;">+</span>
  <img src="https://vuejs.org/images/logo.png" alt="Vue.js Logo" height="96" />
</p>

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
   .env.example .env
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
