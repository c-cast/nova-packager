# Nova Packager by cCast

![PHP from Packagist](https://img.shields.io/packagist/php-v/c-cast/nova-packager?style=plastic)
![Packagist](https://img.shields.io/packagist/l/c-cast/nova-packager?color=yellow&style=plastic)
![Packagist](https://img.shields.io/packagist/dt/c-cast/nova-packager?color=green&style=plastic)

Nova Packager is a newer version of NovaModules, created for **Nova 4.0**.

With Nova Packager you can create modules inside your Laravel Nova application and have different 
folders for each module.

```
composer required c-cast/nova-packager
```

You can easily create a new package using the artisan command: 

```
php artisan nova-packager:create {package}
```

If you want change some configurations in config file you can publish config file:
```
php artisan vendor:publish --tag=nova-packager
```

Here is the list of available features:

- Action
- Card
- Custom Filter
- Dashboard
- Field
- Filter
- Lens
- Metrics (Partition / Value / Trend / Progress)
- Migration
- Model
- Policy
- Resource
- Resource Tool
- Service Provider
- Tool

# License
The MIT License (MIT). Please see <a href="https://github.com/c-cast/nova-packager/blob/master/LICENSE">License File</a> for more information.

# Notes
This package is a newer version of <a href="https://github.com/mycmdev/nova-modules">mycmdev/nova-modules</a> that i'm not going to update anymore.

