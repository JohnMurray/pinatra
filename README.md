# Pinatra

If the name of this project isn't enough to let you know what it is, this is a
Sinatra clone in PHP. Currently this is not a serious project, just a little
something for me to try and learn a little more about PHP. 

My background is
mainly in Ruby and I have a strong love for Sinatra. So, I'm taking about the
task to implement the basic feature-set that is present in Sinatra. Mainly
the minimalistic route matching and hooks that Sinatra exposes for whipping
up dandy little web-apps/api's very quickly.


## Getting Started

A simple hello-world example in good 'ole Sinatra fashion:

```php
# index.php
require 'pinatra.php';

Pinatra::get('/hi', function () { return 'Hello World!'; });
Pinatra::run();
```

```bash
php -S 0.0.0.0:8181
```


Before hooks:

```php
// before everything, set a custom header
Pinatra::before('*', function () { header("MyApp: v${version}"); });

// before user's view their profile, force an update of
// their stream (silly example)
Pinatra::before('/user-profile/:id', function($id) {
  update_user_stream($id);
});
```


After hooks:

```php
// update site's hit-counter (also silly, but you get the point right?)
Pinatra::after('*', function () {
  update_site_hit_counter();
});
```


## Compatability

This little framework is only compatible with PHP v5.4.x since it was just
for fun and I don't care about any sort of backwards compatability.


## On the Calendar

The items that I will be adding/implementing next are (roughly) as follows:

+ ~~Parametric URIs (variables actually passed to the handler functions)~~
+ ~~Refactoring of handle_request function~~
+ ~~Configuration blocks~~
+ ~~Testing before and after hooks~~ (working)
+ ~~POST functionality~~
+ PUT functionality
+ ~~DELETE functionality~~
+ ~~HEAD functionality~~
+ PSR-0 Autoloader (see Slim framework)
+ Ability to embed instances within each other (like Rack-apps)

## Contributing
If you actually like the sound of what I plan to do and would like to take it
a little further, feel free to send me an email at
[me@johnmurray.io](mailto:me@johnmurray.io) or just send me a pull-request! :-]


