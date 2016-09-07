# "Pro" theme section examples for the customizer

This repository is some examples of using the Customize API for adding a "pro" link to the customizer.  

The goal is to provide examples so that theme authors are not bypassing the core API and doing weird things like injecting links into the DOM via JavaScript, which can and will most likely break in future iterations of the customizer in core WordPress.

## Testing

To test the output, simply drop the `trt-customize-pro` folder into the root of your theme like so:

```
/themes
        /your-theme
                /trt-customize-pro
```

Then, load an example with this code in your theme's `functions.php`:

```
require_once( trailingslashit( get_template_directory() ) . 'trt-customize-pro/example-1/class-customize.php' );
```

## Usage

If you decide to use in your theme, make sure to change up all the prefixes, textdomains, and paths.  Everything should be relatively straightforward if you've worked with the customizer.

## Copyright and License

All code, unless otherwise noted, is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

2016 &copy; [Justin Tadlock](http://justintadlock.com).
