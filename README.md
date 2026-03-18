# BlogLog Website

A modern, responsive, and fully functional blog website built using PHP and MySQL. It features an aesthetic glassmorphism UI, a user authentication system, post interactions (likes, comments), and an admin dashboard.

## Features
- **User Authentication**: Register and Login functionality with hashed passwords.
- **Roles**: Distinct permissions for regular users and administrators.
- **Admin Dashboard**: Admins can manage all posts on the platform.
- **Blog Engine**: Users can create, edit, delete, and view posts.
- **Engagement**: A complete liking and commenting system for active post discussions.
- **Search & Filter**: Search functionality and category filtering to navigate posts.
- **Modern UI**: Polished CSS design using `Plus Jakarta Sans`, gradient highlights, and hover animations.

## Setup Instructions

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or a similar local server environment (providing Apache and MySQL).
- PHP 8.x

### Installation
1. Clone this repository into your XAMPP `htdocs` directory (or your preferred document root):
   ```bash
   git clone https://github.com/jiyajalan13/blog-website.git
   ```
2. Start the **Apache** and **MySQL** services from your XAMPP Control Panel.
3. Import the database schema:
   - Open your terminal or command prompt.
   - Navigate to the project directory.
   - Run the following command to initialize the database:
     ```bash
     mysql -u root < setup.sql
     ```
   - Alternatively, you can use phpMyAdmin to manually import the contents of `setup.sql`.
4. Run the project:
   - If placed in `htdocs/blog-website`, access it directly via `http://localhost/blog-website/`.
   - Alternatively, you can start a built-in PHP server from the source directory:
     ```bash
     php -S localhost:8000
     ```
     And then visit `http://localhost:8000/`.

## Database Design
The database (`blog_db`) primarily utilizes the following structure:
- `users`: Stores user credentials (`name`, `email`, `password`, `role`).
- `posts`: Stores blog publications (`title`, `content`, `category`, `author_id`, `views`).
- `comments`: Connects user feedback to posts (`post_id`, `user_id`, `comment`).
- `likes`: Tracks like interactions per post.

## Authors
Originally adapted for AD lab purposes. Managed and updated by [Jiya Jalan](https://github.com/jiyajalan13).
