# How to install this project 

1) Download Repo, zip or clone

2) Copy this in httpd.conf of apache
-------
<Directory "C:/webapps/sites/internship/html">
	Allow from all
   	Require all granted
</Directory>

Alias /internship "C:/webapps/sites/internship/html"
-------

3) Put the project folder into the C:/webapps/sites/

4) Command Line into that project folder

5) Run 'composer install'

6) Test visit :
http://localhost/internship/
http://localhost/internship/login.php/hello/12345