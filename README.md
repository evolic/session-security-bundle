# Session Security Bundle

## About

> **Session Fixation** is a security attack that permits an attacker to hijack a valid user session. Applications that don't assign new session IDs when authenticating users are vulnerable to this attack.
>> https://symfony.com/doc/current/reference/configuration/security.html

Symfony can handle session fixation issue by using one of three different strategies:
* **NONE** - don't change the session after authentication, this is not recommended;
* **MIGRATE** (**default**) - the session ID is updated, but the rest of session attributes are kept;
* **INVALIDATE** - the entire session is regenerated, so the session ID is updated but all the other session attributes are lost.

This bundle was created to provide session security improvements for Symfony 6.0 applications.

Session Security Bundle fixes the issue, when session cookie is hijacked from some user agent/device and used in another one.


### The issue

Let's assume that you have two computers:
* one with Ubuntu, where you use Mozilla Firefox 102.0.1,
* and the second with Windows 10, where you use Google Chrome 100.0;

If you log in into your application as `jane_doe` and then copy session cookie (with name **PHPSESSID**)
from one computer to the other, you will be also logged in as `jane_doe` on that computer.

This is because Symfony does not protect your application against session cookie hijacking.


## Requirements

Source code of this bundle is written in PHP in version 8.0 - the same as Symfony 6.0

Three validators base on [browscap](http://browscap.org/) - a Browser Capabilities Project, which allows you to detect
* in lite version: browser name, browser version, device type, the platform (operating system);
* in full version: above and browser capabilities e.g. if JavaScript, CSS, frames, tables are supported, etc. 


### Browscap installation

To install browscap just download `browscap.ini` file (lite or full) and set the path to it in your `php.ini` file.

Then restart your PHP service (and web server), and you should be able to detect browsers and their platforms based on user agent header.

More about browscap you can [read on php.net website](https://www.php.net/manual/en/function.get-browser.php).


## Bundle installation

To add this bundle to your application just run following command:

```shell
$ composer req loculus/session-security-bundle
```

This command will add the latest version of this bundle to your `config/bundles.php`

Then you need to configure validators and session invalidation strategies.

If you don't do it you will get following error message:

```text
The child config "session_validators" under "session_security" must be configured.
```


### Configuration

You need to create new Yaml file `config/packages/loculus_session_security.yaml`

Minimal configuration of the bundle is as follows:

```yaml
loculus_session_security:
    session_validators: []
    session_invalidation_strategies: []
```

Above configuration:
* does not enable any session validator,
* and does not enable any session invalidation strategy.

So you have this bundle enabled, but your application works as before.


#### Available session validators

You can use following session validators:
- `user_agent_validator` - it bases on `$_SERVER['HTTP_USER_AGENT']` and it is not recommended, because users
  can upgrade their web browsers, which would cause undesired behaviour;
- `ip_address_validator` - it bases on `$_SERVER['REMOTE_ADDR']` and it is also not recommended, because user's
  IP address can frequently change, which would cause undesired behaviour;
- `browser_name_validator` - it bases on browser name, which is provided by browscap library and
  **is highly recommended**; example values: `Firefox`, `Chrome`, `Safari`, `Opera`;
- `browser_platform_validator` - it bases on browser platform (operating system), which is provided by browscap library and
  **is highly recommended**; example values: `Linux`, `Win10`, `iOS`, `Android`;
- `browser_device_type_validator` - it bases on device type, which is provided by browscap library and
  **is highly recommended**; example values: `Desktop`, `Tablet`, `Mobile Phone`;


#### Available session invalidation strategies

You can use following session invalidation strategies:
- `session_regenerate_id_strategy` - regenerates session id and then destroys whole session; this strategy **should be
  enabled** if we want to protect our application against session hijacking;
- `throw_invalid_session_exception_strategy` - throws `InvalidSessionException`, which causes Error 500 for current
  request;
- `throw_cookie_theft_exception_strategy` - throws `CookieTheftException`, which is provided
  with `symfony/security-core`, and forces Symfony to redirect user to page with log in form; this strategy **should be
  enabled** if we want to protect our application against session hijacking;


#### Invalid configuration

You cannot enable nor session validator neither session invalidation strategy, which is not available.

So following bundle configuration will throw the exception:

```yaml
loculus_session_security:
    session_validators:
       - 'unknown_validator'

    session_invalidation_strategies:
       - 'unknown_strategy'
```


#### Recommended configuration

We recommend following configuration:

```yaml
loculus_session_security:
    session_validators:
        - 'browser_name_validator'
        - 'browser_platform_validator'
        - 'browser_device_type_validator'

    session_invalidation_strategies:
        - 'session_regenerate_id_strategy'
        - 'throw_cookie_theft_exception_strategy'
```

In above case we check browser name, platform and device type. We don't check browser version
(such validator is not present in the bundle).

If validation manager detects invalid session, then `InvalidSessionEvent` is dispatched.
Invalidation session listener intercepts this event and then executes invalidation strategy manager, which handles
the issue in the way specified in configuration.

In above case session id is regenerated, invalid session is destroyed, and user is redirected to log in page,
because `CookieTheftException` is being thrown.


## Note for web developers

If you are web developers and use responsive mode in your web browsers you can experience log out.
This is because your user agent header will be different if you specify some mobile device, but you were logged in
on your desktop.

Redirecting to log in page will occur each time, when you change your device in responsive mode.


## Tests

### Unit tests

```shell
php vendor/bin/phpunit
```

### Code coverage

```shell
XDEBUG_MODE=coverage php vendor/bin/phpunit  --coverage-clover=build/reports/phpunit-clover.xml --coverage-html=build/reports/coverage --log-junit=build/reports/phpunit-junit.xml
```
