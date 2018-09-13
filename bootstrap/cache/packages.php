<?php return array (
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
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
  'fideloper/proxy' => 
  array (
    'providers' => 
    array (
      0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
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
  'spatie/laravel-backup' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Backup\\BackupServiceProvider',
    ),
  ),
  'benjamincrozat/laravel-dropbox-driver' => 
  array (
    'providers' => 
    array (
      0 => 'BC\\Laravel\\DropboxDriver\\ServiceProvider',
    ),
  ),
);