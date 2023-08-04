# Portfolio-Blog

Création d'un Portfolio-Blog

> Openclassrooms PHP/Symfony developer course project 5 : develop your own blog using PHP.

[![Codacy Badge]()]()

## Features

- Front office accessible to all users
- Back office accessible to admins only

### Front office

The website includes the following pages :

- Register / log in form
  A navbar & a footer must be present on all pages.
  Footer contains a link to Admin back office.
- BlogPosts : display all articles ordered by latest. Each blog post card must include a title, updated at date, lead paragraph & link to article.
- SinglePost : individual blog post / article pages with title, headline, content, author, updated at date, comments & publish a comment form

### Back office (admin interface)

- Only specific accounts with an admin role can access the back office

## Specs

- PHP 8
- Bootstrap 5
- Bundles installed via Composer :
  - Twig
  - Autoload
  - PHP Mailer

### Success criteria

The website must be responsive & secured. Code quality assessments done via Codacy.

### Required UML diagrams

- use case diagrams
- class diagram
- sequence diagrams

## Set up your environment

If you would like to install this project on your computer, you will first need to [clone the repo](https://github.com/Getssone/Portfolio-Blog) of this project using Git.

```text
#  Replace with your personal SMTP config if you have one
SMTP_PASSWD=ThepasswordlinkedtoyourSMTPaccount
SMTP_USERNAME=email@example.com
SMTP_HOST=smtp.gmail.com
SMTP_PORT=465
# Also used for SMTP as the address contact form submissions are sent to
BLOG_ADMIN_EMAIL=contact@example.com
BLOG_ADMIN_BACKUP_EMAIL=thisismyblog@gmail.com
BLOG_ADMIN_FULLNAME='John Doe'
```

<!-- tabs:start  -->

## **Install on local webserver **

You can install this project on your WAMP, Laragon, MAMP, or other local webserver.
To do so, you will first need to ensure the following requirements are met.

To install this project, you can use [Mamp](https://www.mamp.info/en/windows/) installed on your Computer.
Once your Mamp configuration is up and ready, you can launch the project.

Then go to <http://localhost:Folder/posts> where you should be able to access the blog.

### Requirements

- You need to have [composer](https://getcomposer.org/download/) on your computer
- Your server needs PHP version 8.0
- MySQL

The following PHP extensions need to be installed and enabled :

- pdo_mysql
- mysqli

### Install dependencies

Before running the project, you need to run the following commands in order to install the appropriate dependencies.

`composer install`

<!-- tabs:end  -->

### Import database files

Once the mamp is launched, go to <http://localhost/phpMyAdmin/index.php/> on your browser. You need to import my BDD :"localhost.sql" file into your BDD.

You may need to change the default database name (blog_p5) in the SQL file to match the allocated database name provided by your host.

```sql
-- Base de données : `blog_p5`
-- admin account : 'test5@test.fr'
-- admin password : 'test5'
-- user account : 'test4@test.fr'
-- user password : 'test4'
```

Then, go to the website, register yourself as a user.
In order to become an admin (and therefore be able to write your own blog posts & use the admin dashboard), you need to update the role property of your user and set it to 1.
You can do this manually
