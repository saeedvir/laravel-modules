git commit -m "Fix: Laravel 12 compatibility - defer module registration

- Defer module loading until after Laravel boots
- Fixes 'Target class [cache] does not exist' error
- Modules now register in booted() callback
- Compatible with Laravel 12 service provider lifecycle"
