## Chotot.vn - Web developer recruitment exercises
##### <i>Base on Chotot <code>[assignments.pdf](https://docs.google.com/file/d/0BzVNdDU1AZTQM0RoR0JWT3ZYMG8/edit?usp=sharing)</code></i>
---
### Requirement
The Laravel framework has a few system requirements:

- PHP >= 5.3.7
- MCrypt PHP Extension
- Apache Webserver
- CURL with PHP-CURL Extension

And some of external libraries (included by <code>composer.json</code> file) use in both dev and production environments:

####require

- [guzzle/guzzle](https://github.com/guzzle/guzzle "Guzzle is a PHP HTTP client and framework for building RESTful web service clients")
- [jasonlewis/basset](https://github.com/jasonlewis/basset "A better asset management package for Laravel")

####require-dev

- [way/guard-laravel](https://github.com/JeffreyWay/Laravel-Guard "Instant asset compilation, concatenation, and minification for Laravel 4.")
- [way/phpunit-wrappers](https://github.com/JeffreyWay/PHPUnit-Wrappers "")
- [phpunit/phpunit](http://phpunit.de/manual/3.7/en/installation.html "UnitTest framework for PHP")
- [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper "Complete phpDocs, directly from the source")
- [way/generators](https://github.com/JeffreyWay/Laravel-4-Generators "Rapidly speed up your Laravel 4 workflow with generators")

####Libraries
- [symfony/DomCrawler](https://github.com/symfony/DomCrawler "Subtree split of the Symfony DomCrawler Component.")

####Javascript
- [jQuery](http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js)
- [jQueryUI](http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js)
- [jQueryShapeShift](http://mcpants.github.io/jquery.shapeshift/core/jquery.shapeshift.min.js)
### Installation
#### Composer
Install Composer, take a look at [composer installation guide](http://getcomposer.org/doc/00-intro.md)
##### follow these step  
- step 1 - install composer by download the composer.phar  
<code>curl -sS https://getcomposer.org/installer | php -- --install-dir=/root</code>
- step 2 - install laravel 4 with composer  
<code>git clone git://github.com/duminhtam/laravel.git laravel</code>
<code>cd laravel</code>  
<code>composer install --dev</code> <i>this will install with dev packages</i>

```php
    'Basset\BassetServiceProvider'
    'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider'
    'Way\Generators\GeneratorsServiceProvider'
    'Way\Console\GuardLaravelServiceProvider'
```
and aliases also </i>(in app.php bottom)</i>   

<code>
    'Basset'          => 'Basset\Facade'
</code>
### Configuration
Change the owner of all file and folder to <code>apache:root</code>

<code>chown -R apache:root laravel</code>

#### Apache virtual host configuration
My project root is located at <code>/apache/laravel</code> and the virtual host <code>DocumentRoot</code> must be pointed to <code>/apache/laravel/public</code>
 <pre>
 ```
NameVirtualHost *:80
<VirtualHost *:80>
DocumentRoot /apache/laravel/public
ServerName laravel.local
 <Directory "/apache/laravel/public"> 
         Options Indexes FollowSymLinks MultiViews
         AllowOverride all
 </Directory>
</VirtualHost>
 ```
 </pre>
#### Linux Command Alias
Some <b>linux command alias</b> that will be used in my readme file
my <code>composer.phar</code> is located in <code>/root/composer.phar</code>
```php
    alias composer='php /root/composer.phar'
    alias artisan='php artisan'
    alias phpunit='test'
```
These <code>command only work when you are at the project root file</code>, my project root <code>apache/laravel</code>, use <code>pwd</code> to view your current work directory. Unless you are in your document root, go to it directory with command <code>cd /apache/laravel</code>

####Compile CSS and JS
This will create script and css to compiled folder

<code>artisan basset:build -p chotot</code>

Configuration is located in

<code>app/config/packages/jasonlewis/basset/config.php</code>
#### Environment
The default environment is <code>production</code>, you can keep this to run without complex config or you can configure as local, take a look at [laravel environment configuration](http://four.laravel.com/docs/configuration) (sorry I dont have enough time for document it).  
[Change and view machine name tutorial](http://www.cyberciti.biz/faq/howto-change-my-hostname-machine-name/) if use want to change environment.
#### Database
You can you any database you want with laravel support, please config in <code>app/config/database.php</code>  

<code>
'default' => 'sqlite',
</code>  <i>I use sqlite, so that no configuration needed. The database file is located at <code>app/database/production.sqlite</code> <code>production</code> does not mean the environtment, it is only the file name in <code>configuration</code> bellow:</i>.
```php
    'sqlite' => array(
		'driver'   => 'sqlite',
		'database' => __DIR__.'/../database/production.sqlite',
		'prefix'   => '',
	),
```


Other database support:  

<code>mysql, pgsql, sqlsrv</code>

A sample <b>mysql</b> config if you want to:

```php
    'mysql' => array(
        		'driver'    => 'mysql',  
    			'host'      => 'localhost',  
    			'database'  => 'ads',  
    			'username'  => 'ads_user',  
    			'password'  => 'qweQWE123!!!',  
    			'charset'   => 'utf8',  
    			'collation' => 'utf8_unicode_ci',  
    			'prefix'    => '',  
    		),  
```

and change the default database engine to <code>mysql</code> in <code>app/config/database.php</code>  

<code>
'default' => 'mysql',
</code>
#### Migration
Use artisan to migrate your database, this will <b>create</b>(<code><b>database table, fields, index, view</b></code>)  

<code>
    artisan migrate
</code>

The migration schema file located in <code>app/config/database/migrations/2013_08_10_061829_create_ads_table.php</code> file.  

This will create the table with <b>structured</b> and <b>view</b> bellow:
```php
    //file <b>2013_08_10_061829_create_ads_table.php</b>
    Schema::create('ads', function(Blueprint $table) {
    			$table->increments('id');
    			$table->string('title', 255);
    			$table->text('description');
    			$table->float('price', 10);
    			$table->string('currency', 3);
    			$table->tinyInteger('col', 2)->default(1);
    	     	$table->tinyInteger('row', 2)->default(1);
    			<b>$table->string('url', 255)->unique();</b> <i>//url with unique index, be used in <b>"new ads check"</b></i>
    			$table->string('date', 255);
    			$table->string('img', 255);
    			$table->string('category', 255);
    			$table->string('date_posted', 25);
                //create required indexes
                 $table->index('row');
                $table->index('col');
    			$table->timestamps();
    		});
            //create required views
            DB::statement('CREATE VIEW new_ads AS
                          SELECT *
                          FROM ads
                          ORDER BY id DESC;
                          ');
```

Database <code>stucture image</code> (<code>sqlite</code>):

![db structure image](http://i.imgur.com/cRQDMpe.png)

Change the owner of all file and folder to <code>apache:root</code> when run migration

<code>chown -R apache:root laravel</code>

### JS config

The js configuration is located in <code>app/models/ChoTot.php</code>

```php
    const CONFIG_MAX_COLS = 10; //col
    const CONFIG_RUN_INTERVAL = 1000; //ms
    const CONFIG_IDLE_INTERVAL = 5; //idle second
```
### Running
All routes are configured in <code>app/routes.php</code> file.  
Framework included index route still be kept
```php
Route::get('/', function()
{
    return View::make('hello');
});
```
#### Index Route
Browser URL:
<code>/chotot</code>

Image

[chotot crawler index](http://i.imgur.com/WY5sL1b.png)

#### Cron Route
This will crawl frist 20 ads result in chotot.vn /hochiminh and store in db, return the json result also

Browser URL:
<code>/chotot/cron</code>

JSON result image

![a](http://i.imgur.com/G8HnZqL)

#### Update Route
This post route is filtered by <code>csrf</code>. The <code>chotot/update</code> route only accept post method with <code>params</code> described bellow:

<code>{_token,ads:{ id,position } }</code>

<code>_token</code> is the <code>csrf token</code> name

<code>ads</code> is the <code>ads array</code> with <code>id</code> and </code>position</code>, this is <code>one update query</code> for all object when it was arranged, <code>no loop</code> update(<code>performance</code> query).


Route code in <code>app/routes.php</code>:
```php
Route::group(array('before' => 'csrf'), function()
{
    Route::post('chotot/update', 'ChoTotController@postUpdate');
});
```
Browser URL:
<code>/chotot/update</code>

### Testing
Run <code>test</code> alias of <code>phpunit</code> command from document root to get the test result.
