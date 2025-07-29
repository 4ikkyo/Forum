# Forum

This project is a simple forum system built with PHP and MySQL. It was developed during my bachelor's studies as a course project and is now included in my portfolio as an example of an project.

## Features

- User registration and authentication  
- Creating topics and adding comments  
- Avatar uploads  
- Displaying user online status  

## File Structure

Key directories and files:

- `index.php` – user profile page after login  
- `forum.php` – forum topics list  
- `question.php` – view a single topic and its comments  
- `signin.php` / `signup.php` – login and registration forms  
- `vendor/` – database scripts (`connect.php`, `signin.php`, `signup.php`, etc.)  
- `assets/` – styles, scripts, and images  
- `uploads/` – user-uploaded files  
- `SQLDump.sql` – database dump  

## Local Setup

Development and testing were done using [OpenServer](https://ospanel.io/). To run the project locally:

1. Install OpenServer and launch it.  
2. Create a database named `forum` and import the `SQLDump.sql` file.  
3. Place the project files in the OpenServer websites directory.  
4. Open the site in your browser via the selected domain (typically `http://localhost/`).
