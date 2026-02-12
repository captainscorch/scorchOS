<p align="center">
	<a href="https://captainscor.ch" target="_blank"><img src="https://captainscor.ch/img/captainscorch_logo.svg" width="50" style="display: inline-block; vertical-align: middle;"></a>
</p>

<p align="center">
	<a href="https://github.com/captainscorch/scorchOS"><img alt="License" src="https://img.shields.io/github/license/captainscorch/scorchOS"></a>
</p>

# scorchOS

scorchOS is my personal website, portfolio, blog, and playground, mixing my own aesthetic combined with taking inspiration from my favorite parts of macOS, command line interfaces and modern web applications. It is built primarily with Laravel, Inertia.js, Vue.js, Tailwind CSS.

## Tech Stack

- **[Laravel](https://laravel.com)**
- **[Vue.js](https://vuejs.org)**
- **[Inertia.js](https://inertiajs.com)**
- **[Tailwind CSS](https://tailwindcss.com)**
- **[Vite](https://vitejs.dev)**

## System requirements & versions

- Laravel: 12.x
- Vue.js: 3.x
- Inertia.js: 2.x
- Tailwind CSS: 4.x
- Node.js: 24.x
- PHP: 8.3+

## How to Install and Run the Project

To set up and run the project locally, follow these steps:

1. Ensure you have [Laravel Valet](https://laravel.com/docs/12.x/valet) installed globally. You can install it with Composer:

    ```bash
    composer global require laravel/valet
    valet install
    ```

2. Clone the repository to your local machine.

3. Navigate to the project directory in your terminal.

4. Copy the `.env.example` file to a new file named `.env`:

    ```bash
    cp .env.example .env
    ```

5. Install the project dependencies by running:

    ```bash
    composer install
    ```

6. Generate an application key:

    ```bash
    php artisan key:generate
    ```

7. Link the project with Valet to enable automatic `.test` domain routing:

    ```bash
    valet link
    ```

8. Secure the test domain with a TLS certificate:

    ```bash
    valet secure scorchos
    ```

9. Install Node dependencies and start Vite in development mode:

    ```bash
    npm install
    npm run dev
    ```

10. Visit the application in your browser at [https://scorchos.test](https://scorchos.test).

## Blog & Playground demos

Demos are auto-discovered from `resources/js/components/blog/demos/`. No need to register components in page files.

- **Blog only:** Add a `.vue` file, then in your post use `::interactive[ComponentName]` and add `components: ['ComponentName']` to frontmatter (name = filename without `.vue`).
- **Playground:** Add the `.vue` file, then add one entry to the `CONFIG` array in `resources/js/components/blog/demos/playground-experiments.ts` (componentName, id, title, description, category, tags, code, language). Optionally add `ComponentName: 'playground-id'` to `COMPONENT_TO_PLAYGROUND_ID` in `registry.ts` so the blog can show “View in Playground”.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
