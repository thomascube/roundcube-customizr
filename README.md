Roundcube Customizr
===================

Plugin to customize logo, watermark image and stylesheets by config.

Download and install via http://plugins.roundcube.net

Configuration Options
---------------------

Set the following options directly in Roundcube's main config file or via 
[host-specific](http://trac.roundcube.net/wiki/Howto_Config/Multidomains) configurations:

```php
// define a custom watermark image (relative or absolute URL)
$config['custom_watermark_image'] = './skins/custom_watermark.png';

// define a custom URI to be displayed instead of the empty watermark page
$config['custom_watermark_uri'] = '';

// define a custom favicon image (emtpy string to remove it)
$config['custom_favicon'] = './skins/custom_favicon.ico';

// defines a custom CSS file which is added to every page
$config['custom_stylesheet'] = './skins/custom_stylez.css';
```
