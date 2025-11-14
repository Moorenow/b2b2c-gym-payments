<table align="center">
  <tr>
    <td>
      <img src="https://laravel.com/img/logomark.min.svg" alt="Logo de Laravel" height="80" />
    </td>
    <td>
      <img src="https://vuejs.org/images/logo.png" alt="Logo de Vue.js" height="80" />
    </td>
  </tr>
</table>

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
