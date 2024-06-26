<p align="center">
    A starting point to create your next Filament 3 💡 app. With pre-installed plugins, pre-configured, and custom page. So you don't start all over again.
</p>

#### Features

-   🛡 [Filament Shield](#plugins-used) for managing role access
-   👨🏻‍🦱 Customizable profile page from [Filament Breezy](#plugins-used)
-   🌌 Managable media with [Filament Spatie Media](#plugins-used)
-   🖼 Theme settings for changing panel color
-   💌 Setting mail on the fly in Mail settings
-   🅻 Lang Generator

#### Latest update
###### Version: v1.15.xx
- New Products Resource UI form
- Dashboard Chart Widgets
- Bugs fix & Improvement
- Products API Resource
- Posts API Resource

#### Getting Started

Create project with this composer command:

```bash
composer create-project tyronemurg/superduper-filament-starter-kit
```

Setup your env:

```bash
cd superduper-filament-starter-kit
cp .env.example .env
```

Run migration & seeder:

```bash
php artisan migrate
php artisan db:seed
```

<p align="center">or</p>

```bash
php artisan migrate:fresh --seed
```

Generate key:

```bash
php artisan key:generate
```

Run :

```bash
npm run dev
OR
npm run build
```

```bash
php artisan serve
```

Now you can access with `/admin` path, using:

```bash
email: superadmin@starter-kit.com
password: superadmin
```

*It's recommend to run below command as suggested in [Filament Documentation](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) for improving panel perfomance.*

```bash
php artisan icons:cache
```

#### Language Generator
This project include lang generator. 
```
php artisan superduper:lang-translate [from] [to]
```
Generator will look up files inside folder `[from]`. Get all variables inside the file; create a file and translate using `translate.googleapis.com`.

This is what the translation process looks like.
```
❯ php artisan superduper:lang-translate en fr es

 🔔 Translate to 'fr'
 3/3 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100% -- ✅

 🔔 Translate to 'es'
 1/3 [▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░]  33% -- 🔄 Processing: page.php
```
###### Usage example
* Single output
```
php artisan superduper:lang-translate en fr
```
* Multiple output
```
php artisan superduper:lang-translate en es ar fr pt-PT pt-BR zh-CN zh-TW
```
###### If you are using json translation
```
php artisan superduper:lang-translate en fr --json
```

#### Plugins

These are [Filament Plugins](https://filamentphp.com/plugins) use for this project.

| **Plugin**                                                                                          | **Author**                                          |
| :-------------------------------------------------------------------------------------------------- | :-------------------------------------------------- |
| [Filament Spatie Media Library](https://github.com/filamentphp/spatie-laravel-media-library-plugin) | [Filament Official](https://github.com/filamentphp) |
| [Filament Spatie Settings](https://github.com/filamentphp/spatie-laravel-settings-plugin)           | [Filament Official](https://github.com/filamentphp) |
| [Filament Spatie Tags](https://github.com/filamentphp/spatie-laravel-tags-plugin)                   | [Filament Official](https://github.com/filamentphp) |
| [Shield](https://github.com/bezhanSalleh/filament-shield)                                           | [bezhansalleh](https://github.com/bezhansalleh)     |
| [Exceptions](https://github.com/bezhansalleh/filament-exceptions)                                   | [bezhansalleh](https://github.com/bezhansalleh)     |
| [Breezy](https://github.com/jeffgreco13/filament-breezy)                                            | [jeffgreco13](https://github.com/jeffgreco13)       |
| [Logger](https://github.com/z3d0x/filament-logger)                                                  | [z3d0x](https://github.com/z3d0x)                   |
| [Ace Code Editor](https://github.com/riodwanto/filament-ace-editor)                                 | [riodwanto](https://github.com/riodwanto)           |


### License

Filament Starter is provided under the [MIT License](LICENSE.md).

If you discover a bug, please [open an issue](https://github.com/tyronemurg/filament-starter-kit/issues).

