# MyLabBook

This repository contains all the code needed for a fully functional Electronic Lab Notebook (ELN) based on the Drupal CMS.

Note that this repository is in a format that makes it **very simple to install**. This is why we take the somewhat unorthodox approach of including the vendor folder as well as why a fixed database is used (which means using fixed usernames and passwords to start out, but you can easily change as desired after installation). This is the approach that many Drupal theme vendors use to ease the installation process. And **ease of installation is one of our primary goals**. If you are at all familiar with setting up a LAMP stack, then you should be able to install this fully working version in about 15 minutes or so. 

## Installation

To install this demo on your own server, follow these steps using your terminal (assuming a Linux based server).

1. Clone this repository using git

``` bash
git clone https://github.com/mylabbook/mylabbook.git 
```

This will create a subdirectory on your server called mylabbook

2. Create an empty MySQL or equivalent database (e.g. MariaDB). In these instructions we will call the database elndb.

3. In the mylabbook/dbsql subdirectory you should see the elndb.sql file. Use that file to populate the database you just created. For example, on the command line you can do the following.

``` bash
   mysql -u {username} -p {password} {elndb} < elndb.sql
```

Where {username} is the username for your mysql server and {password} is the password for your {username} and {elndb} is the name of the database that you created.

4. Now open the domain that you created for this installation, e.g. mylabbook.localhost.

5. You should see the standard installation screens for installing Drupal. Choose the standard installation.

6. When it asks you for the database, use the database that you created (e.g. elndb) and the username and password for the database.

7. You should then see a screen that says that your application is created. This will be using the data from the database that you just created (e.g. elndb).

**At this point, you should have a fully working Drupal based ELN that you can modify and update as desired.**

To login as the admin, use the following credentials.

``` bash
Username: admin
Password: Admin1234!!!!
```

On the home page of the website (before you login) there is information for other user credentials as well - Student and Labadmin, along with their passwords.
