# Back-end Assignment

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

- Generate the username as full surname and 3 letter from first name (Example: Johnny Depp will have username “deppjoh”)
- Logged user can create new blog and add new comment
- Maximum length for subject on the new blog post is 64 characters
- Maximum length for comment is 255 characters
- Provide admin account which can delete post or comment.
- Blog feed should list all posts and associated title, author, date, and description. It should be sorted by publish date from newest first
- Users can view individual blog posts in a separate page
- Users can view comments for a blog post
- When deleting the comment or blog post it should be soft-deleted and moved to the trash bin from which can be deleted permanently
- The comment or blog post older than 3 hours should be deleted automatically.
- Admin can restore comment or blog post

<hr>

# Frontend Developer

Create a simple front-end that will enable following by communicating with the API:
- view blog post,
- login
- comment
- admin functionalities 

Basically, so it can be tested.

Please try to use Migration and Seed to create database and record. It will be great if you can add some unit test in a code.

<hr>
