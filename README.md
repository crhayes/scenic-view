Scenic View
===========

Scenic View is a tiny library that provides view and template inheritance. 
The syntax is straight PHP but is very clean and expressive.

The following examples use PHP short syntax (to provide cleaner views) which is fine
if you have control over your own server. **NOTE:** you should definitely use ```<?php ?>```
if you are planning to distribute your code or do not have admin access to your server.

## Loading and Rendering a View
```php
$view = new Scenic\View('path/to/views');

echo $view->render('index.php', array('title' => 'Welcome', 'name' => 'Chris'));
```

## View Inheritance and Templating
### Layouts
Create a layout (template) file that other files will extend. The `$show()` method displays
a section of content that's defined by a child view extending it.
```php
<html>
<head>
  <title><?= $title ?></title>
</head>
<body>
  <? $show('content') ?>
</body>
</html>
```

### Views
Create your view that extends the layout and provides the content for the section.
```php
<? $extends('template.php') ?>

<? $section('content') ?>
  <h1>Welcome, <?= $name ?></h1>
<? $end() ?>
```

### Extending Parent's Content
What if you want to provide content in the parent, but extend that in the child? You can!

Define a section in the template.
```php
<html>
<head>
  <title><?= $title ?></title>
</head>
<body>
  <? $section('content') ?>
    <h1>Welcome, <?= $name ?></h1>
  <? $end() ?>
</body>
</html>
```

Use the `@parent` key in the view to specify where you want the parent content to appear.
```php
<? $extends('template.php') ?>

<? $section('content') ?>
  @parent
  <p>We hope you have a great stay!</p>
<? $end() ?>
```

or

```php
<? $extends('template.php') ?>

<? $section('content') ?>
  <p>We hope you have a great stay!</p>
  @parent
<? $end() ?>
```

### Partials
Including partial views, like headers or footers, is easy as well.
```php
<html>
<head>
  <title><?= $title ?></title>
</head>
<body>
  <? $section('content') ?>
    <h1>Welcome, <?= $name ?></h1>
  <? $end() ?>
  
  <? $include('footer.php') ?>
</body>
</html>
```
