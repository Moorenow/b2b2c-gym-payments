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
