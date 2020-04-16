User Protect
============

CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------

The User Protect module allows fine-grained access control of user
administrators, by providing various editing protection for users. The
protections can be specific to a user, or applied to all users in a role.

The following protections are supported:

 * Username
 * E-mail address
 * Password
 * Status
 * Roles
 * Edit operation (user/X/edit)
 * Delete operation (user/X/cancel)

 * For a full description of the module visit:
   https://www.drupal.org/project/userprotect

 * To submit bug reports and feature suggestions, or to track changes visit:
   https://www.drupal.org/project/issues/userprotect


REQUIREMENTS
------------

This module requires no modules outside of Drupal core.


INSTALLATION
------------

 * Install the User Protect module as you would normally install a contributed
   Drupal module. Visit https://www.drupal.org/node/1897420 for further
   information.


CONFIGURATION
-------------

    1. Navigate to Administration > Extend and enable the module.
    2. Navigate to Administration > Configuration > People > User protect for
       configurations.

There are two types of protection rules:

 * User based protection rules:
   This user will be protected for all users except: "User 1" (admin), the
   protected user itself, and users with the permissions to "Bypass all user
   protections".
 * Role based protection rules:
   This role will be protected for all users except: "User 1" (admin), and users
   with the permissions to "Bypass all user protections".

A protection rule prevents any user to perform the selected editing operations
(such as changing password or changing mail address) on the specified user.
There are two exceptions in which a configured protection rule does not apply:

 * The logged in user has permission to bypass the protection rule.

 * The specified user is the current logged in user.
   Protection rules don't count for the user itself. Instead, there are
   permissions available to prevent an user from editing its own account,
   username, e-mail address, or password.

Protected fields will be disabled or hidden on the form at user/X/edit. The edit
and delete operations are protected by controlling entity access for the
operations 'update' and 'delete'.


MAINTAINERS
-----------

 * Youri van Koppen (MegaChriz) - https://www.drupal.org/user/654114
