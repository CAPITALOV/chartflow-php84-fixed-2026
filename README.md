# ChartFlow

> Сервис построения графиков и диаграмм: пользователи создают запросы, данные
> обрабатываются, результат формируется в виде готовых графиков.
>

## Стек

| Компонент | Версия | Примечание |
|---|---|---|
| PHP | 8.4 (на сервере — 8.4.27) | миграция со старой версии выполнена в 2026 г. |
| Фреймворк | Yii 2.0.31 | стабильная LTS-ветка Yii2, совместима с PHP 8.4 |
| СУБД | MySQL | подключение описано в `config/db.php` (в репо не публикуется) |
| ОС сервера | Debian 13 (trixie) | ядро 6.12 |
| Веб-сервер | Nginx + PHP-FPM | |
| Почта | Swift Mailer | SMTP-провайдер: r01.ru |

## Структура проекта

```
.
├── app/                 # основной код приложения
├── commands/            # консольные команды (Yii)
├── config/              # конфигурация приложения
├── controllers/
├── mail/                # шаблоны писем (html и text)
├── migrations/          # миграции БД
├── models/
├── service/             # сервисный слой
├── views/
├── public_html/         # DOCUMENT_ROOT (точка входа, веб-сервер смотрит сюда)
└── vendor/suffra/       # собственный пакет, хранится в репозитории
```

## Конфигурация и секреты

Часть конфигов содержит секреты и **не публикуется в репозитории** (закрыты
в `.gitignore` и `config/.gitignore`):

- `config/db.php` — подключение к БД;
- `config/mailerTransport.php` — SMTP-логин и пароль;
- `config/suffra.php` — платёжный/сервисный конфиг;
- `config/authClientCollection.php` — ключи OAuth-провайдеров.

На рабочем сервере эти файлы создаются вручную. После клонирования
репозитория скопируйте недостающие конфиги с боевого окружения или создайте
по аналогии с `config/web.php`.

## Требования и установка

1. PHP 8.4 с расширениями `mbstring`, `pdo_mysql` (минимальный набор; сверьте по composer.json).
2. MySQL и nginx с PHP-FPM.
3. Зависимости ставятся Composer'ом:

```bash
composer install
```

4. Накатить миграции:

```bash
php yii migrate
```

## Разработка

Ветки: `main` — основная. Изменения коммитятся с осмысленными сообщениями:

```bash
git add -A
git commit -m "Краткое описание изменения"
git push origin main
```

Перед публикацией проверяйте, что в коммит не попали файлы с секретами:

```bash
git check-ignore -v config/db.php config/mailerTransport.php
```

## История проекта

- Проект разрабатывался с ~2015 года (старейшие миграции датированы 2015 г.).
- В 2026 году выполнен переезд на новый сервер (Debian 13) и обновление PHP
  до 8.4 с адаптацией кода.
- Репозиторий `chartflow-php84-fixed-2026` — актуальная ветка проекта,
  синхронизированная с рабочим сервером.

## Дорожная карта

- [ ] Обновить устаревшие зависимости (см. `composer outdated`).
- [ ] Автоматизировать деплой (CI/CD, Actions).
- [ ] Покрыть ключевые сервисы тестами (PHPUnit).

## Лицензия

См. [LICENSE](LICENSE). версия 2015 php 7.1

## Деплой

Кодовая база одна и обслуживает два домена:

- **rus.chartflow.ru** — русская версия, основная рабочая (здесь выполнен апгрейд до PHP 8.4 и Yii 2.0.31);
- **chartflow.ru** — международная версия.

Код и конфигурация доменов идентичны; различается только подключение к базе данных (`config/db.php`, в репозиторий не входит). Старые копии кода и резервные файлы `.bak*` в репозиторий не попадают.

Yii 2 Basic Application Template
================================

Yii 2 Basic Application Template is a skeleton Yii 2 application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install from an Archive File

Extract the archive file downloaded from [yiiframework.com](http://www.yiiframework.com/download/) to
a directory named `basic` that is directly under the Web root.

You can then access the application through the following URL:

~~~
http://localhost/basic/web/
~~~


### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this application template using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0-beta4"
php composer.phar create-project --prefer-dist --stability=dev yiisoft/yii2-app-basic basic
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
~~~


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTE:** Yii won't create the database for you, this has to be done manually before you can access it.

Also check and edit the other files in the `config/` directory to customize your application.
