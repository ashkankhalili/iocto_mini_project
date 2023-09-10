# iocto_mini_project
Simple Test Case Management System
Simple Test Case Management System Documentation

Introduction
This document provides an overview and documentation of the Simple Test Case Management System (STCMS), an application developed for testing purposes and commissioned by IOCTO. STCMS is a web-based platform designed to facilitate the management of test cases, projects, and users. It features login and registration functionality, an admin panel, and a user panel, offering distinct capabilities for administrators and regular users.

Project Overview

Project Name: Simple Test Case Management System (STCMS)
Technologies Used: PHP, MySQL
Project Type: Web Application

Key Features
1. User Authentication
STCMS provides secure user authentication with features including:
•	User Registration: New users can create accounts with unique usernames and valid email addresses.
•	Login: Registered users can securely log in using their email addresses or usernames.
•	Password Hashing: User passwords are securely hashed before being stored in the database.

2. Admin Panel
The admin panel is accessible only to administrators and offers the following capabilities:
User Management:
  	Create, edit, and delete user accounts.
Update user details, including usernames, email addresses, and passwords.


Project Management:
  	Create new projects.
Edit project details, including project names and descriptions.
Delete projects.
Test Case Management:
Create and manage test cases.
Assign test cases to specific projects.
Edit and update test case details.
User Assignment
Assign projects to users.
Dashboard:
Overview of system activity and statistics.

3. User Panel

The user panel is designed for regular users and offers the following features:

Project Viewing:
View a list of projects assigned to the user.
Test Case Management:
View and access test cases associated with assigned projects.
Update test case status.
Profile Management:
Update user profile details, including first name, last name, and email address.
Change passwords for added security.




System Architecture

STCMS follows a client-server architecture where the client is a web browser and the server handles HTTP requests and communicates with the MySQL database.

Frontend: HTML, CSS, JavaScript 
For the front ed
Backend: PHP
Database: MySQL

Installation and Setup

1. Database Configuration:
Create a MySQL database and import the database schema from the provided SQL file.
Update the `db_config.php` file with your database connection details.

2. Web Server:
Deploy the project on a web server with PHP support.

3. Access:
Access the project using a web browser.

Usage

Admin Login: Admins can access the admin panel by logging in with their credentials.
User Login: Regular users can log in to access the user panel and manage their assigned projects and test cases.
Registration: New users can create accounts via the registration page. 

Security Considerations

-User passwords are securely hashed using PHP's password_hash function.
Access control is implemented to restrict unauthorized access to admin-only functionalities.
SQL injection prevention through the use of prepared statements.

Conclusion

The Simple Test Case Management System (STCMS) is a robust and user-friendly web application designed to simplify the management of test cases, projects, and users. It provides an efficient platform for administrators and users to streamline their testing processes and enhance project management. For further inquiries or assistance, please contact the project developers.

For inquiries or support, please contact the project development team.

Notes: 
This documentation provides an overview of the STCMS project. Detailed technical documentation may be available separately.

In the development of the Simple Test Case Management System (STCMS), we have leveraged the AdminLTE framework, specifically version 3.2.0, as the front-end foundation. AdminLTE is a popular open-source admin dashboard and control panel theme built on top of the Bootstrap framework. Its rich set of user interface components, sleek design, and responsive layout make it an ideal choice for creating modern and visually appealing web applications.
Website: https://adminlte.io/
By utilizing AdminLTE as the front-end framework for STCMS, we have aimed to create a visually appealing, responsive, and feature-rich application that simplifies test case management while maintaining a high standard of design and usability. This integration allows us to focus on the core functionality of STCMS while leveraging the power and flexibility of AdminLTE for the user interface.
