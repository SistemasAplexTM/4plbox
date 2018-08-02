<?php return array (
  'fideloper/proxy' => 
  array (
    'providers' => 
    array (
      0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    ),
  ),
  'barryvdh/laravel-dompdf' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\DomPDF\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
    ),
  ),
  'laracasts/utilities' => 
  array (
    'providers' => 
    array (
      0 => 'Laracasts\\Utilities\\JavaScript\\JavaScriptServiceProvider',
    ),
    'aliases' => 
    array (
      'JavaScript' => 'Laracasts\\Utilities\\JavaScript\\JavaScriptFacade',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'yajra/laravel-datatables-oracle' => 
  array (
    'providers' => 
    array (
      0 => 'Yajra\\DataTables\\DataTablesServiceProvider',
    ),
    'aliases' => 
    array (
      'DataTables' => 'Yajra\\DataTables\\Facades\\DataTables',
    ),
  ),
  'nztim/mailchimp' => 
  array (
    'providers' => 
    array (
      0 => 'NZTim\\Mailchimp\\MailchimpServiceProvider',
    ),
    'aliases' => 
    array (
      'Mailchimp' => 'NZTim\\Mailchimp\\MailchimpFacade',
    ),
  ),
  'caffeinated/shinobi' => 
  array (
    'providers' => 
    array (
      0 => 'Caffeinated\\Shinobi\\ShinobiServiceProvider',
    ),
    'aliases' => 
    array (
      'Shinobi' => 'Caffeinated\\Shinobi\\Facades\\Shinobi',
    ),
  ),
);