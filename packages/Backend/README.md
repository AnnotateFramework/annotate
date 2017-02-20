Backend package for Annotate Framework
======================================

This package provides powerfull administration.

Installation
------------

Note: this works when annotate/sandbox is installed

Run:

    composer require annotate/backend:@dev

Extend your presenter with `Annotate\Modules\Application\ModularPresenter`.

Register modules routes provider with adding `@Annotate\Backend\Routing\BackendRouteProvider` to `app/config/app.neon` after `DefaultRouteProviderService`

Register authenticators and authorizators with adding them as services into `app/config/services.neon` file:

    -
        class: App\Services\DummyAuthorizator
        tags: [kdyby.subscriber]
    -
        class: App\Services\DummyAuthenticator
        tags: [security.authenticator]
        
Note: You can use any authenticator and authorizator. When using custom authenticator set correct authenticator key in `vendor/annotate/security/src/Components/LoginForm.php` (will be fixed soon). When using DummyAuthenticator use `admin` as login and password.

##Do not use DummyAuthenticator in production!##

Hint!
-----

For better experience install also packages `annotate/themes` and `annotate/packages`

    
Uninstall
---------

Just remove line with `annotate/backend` from composer.json and run `composer update`

Revert changes made along with installation process.
