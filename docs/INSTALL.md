XtraUpload v3 INSTALL
=====================

**It is very simple to install.**

1. You create new database.(via mysqladmin, command-line: mysql -u root -pxxxxx, etc...)  
2. If you can use mod_rewrite, edit RewriteBase into .htaccess.  
   ex: RewriteBase /xtraupload-v3  
   If you cannot use mod_rewrite, edit application/config/config.php.template.  
```php
$config['index_page'] = 'index.php';
```
3. Just put the Web server simply.  
   ex: /var/www/html/xtraupload-v3  
4. Go to your web site!  
   ex: http://yoursite.ltd/xtraupload-v3/
