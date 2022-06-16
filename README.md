# Commands to run project with Docker

(should be run in root folder)
<br>
composer install
<br>
./vendor/bin/sail up -d
<br>
./vendor/bin/sail php artisan migrate
<br>
./vendor/bin/sail php artisan db:seed
<br>
./vendor/bin/sail npm install
<br>
./vendor/bin/sail npm run dev
<br>
./vendor/bin/sail php artisan schedule:work
<br>
open 'localhost' in browser
<br>

Please, use next credentials

- login: admin@admin.admin
- pass: admin
<br>

to login as 'admin' or create new regular user on 'Register' page


# Commands to run project without Docker

install docker
<br>
continue with instructions above :)

# Task's requirements

Business Requirements

Create an API with public endpoints that will support following points:
- Create a simple login/register page for the user
- Use form validation for registration form

Registration form should contain:
- Name
- Surname
- Nickname
- Phone
- Email
- Address
- City
- State
- ZIP

<hr>

- 'username' attribute should be generated as full surname and 3 letter from first name (Example: Johnny Depp will have username “deppjoh”)
- Logged user can create new blog, post or add new comment
- Maximum length for subject on the new blog post is 64 characters
- Maximum length for comment is 255 characters
- Blog feed should list all posts and associated title, author, date, and description. It should be sorted by publish date from newest first
- Users can view individual blog posts in a separate page
- Users can view comments for a blog post
- Provide admin account which can delete blog, post or comment.
- When comment, post or blog deleted it should be soft-deleted and moved to the trash list in 'My account' from which can be deleted permanently or restored
- The comment, post or blog soft-deleted more than 3 hours ago should be permanently deleted automatically.

Please try to use Migration and Seed to create database and record. It will be great if you can add some unit test in a code.

<hr>

# Frontend Developer

Create a simple front-end that will enable following by communicating with the API:
- view blogs, posts, commentaries
- login/register
- leave commentaries
- admin functionalities 

Basically, so it can be tested.

<hr>

# What was used for creating:

- Laravel (with Sail (Docker) environment)
- REST API (with cursor pagination for data lists)
- Sanctum (simple auth and middleware with admin rules)
- Nested Resource Controllers
- Custom Resource Controller methods
- Custom Resource routing for custom methods
- specified Resources, Requests files
- models with nested relations and Soft delete/restore/prunable functional
- DB seeder
- Vue
- VueRouter
