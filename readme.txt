/*
* SPENCER FRAISE
* CODE MONKEYS LLC
* WWW.CODEMONKEYSLLC.COM
*/

This is a custom oop/mysqli user management system/framework with login/registration functionality, salted sha256 hashed passwords,
forgot password hashed and salted token, on-page token generation/verification to prevent cross-site attacks, user profiles,
multiple user types (manager, sales, type2, type1), and basic administrator panel.

/*
* DOCUMENTATION
*/

// BASIC STRUCTURE
------------------
The main site and the admin section are separated using different index.php, template, modules, and js file.
This allows for more customization and different functionality for the admin section.
The admin section however loads the same class files to reduce redundancy.

The main flow works as follows:
index.php-> // sets include path, instantiates user and sets user id and type and sets site data (name, description, logo)
    /core/init.php-> // sets db, cookie, session, and token options, autoloads classes in /classes directory and includes sanitize function
    /template/template.php->  // main html wrapper
        /modules/header.php // loads logo and login/register/logout/forgot password
        /helpers/router.php-> // loads views pages
            /views // loads the appropriate option/index.php according to the option used in url
                   // (eg. index.php?option=profile would load /views/profile/index.php).
                   // If no option loaded it loads /views/index.php which display the appropriate homepage from /views/homepages depending on user type
        /modules/footer // displays footer content

The admin flow works as follows:
/administrator/index.php-> // sets include path, instantiates user and sets user id and type and sets site data (name, description, logo)
    /core/init.php-> // sets db, cookie, session, and token options, autoloads classes in /classes directory and includes sanitize function
    /administrator/template/template.php->  // main html wrapper
        /administrator/modules/header.php // loads logo and login/register/logout/forgot password
        /administrator/modules/topmenu.php // loads the top navigational menu which displays links to admin sections according to permissions of user type
        /administrator/helpers/router.php-> // loads views pages
            /administrator/views // loads the appropriate option/index.php according to the option used in url
                   // (eg. index.php?option=profile would load /views/profile/index.php).
                   // If no option loaded it loads /views/index.php which display the appropriate admin homepage from /administrator/views/homepages depending on user type
        /administrator/modules/footer // displays footer content

// SITE MANAGEMENT
------------------
Managers have access to the site config, site info, and manage user section of the admin panel.
Other user types can have access to other sections as they're created by allowing them access to the link and allowing them access within the view file of that section

SITE CONFIG:
    - Set the option to require email verification or not
    - Set the email to be sent if verification is required
    - Add a new salt prefix and suffix

SITE INFO:
    - Change the site logo
    - Change the site title/name
    - Change the site description

MANAGE USERS:
    - Promote or demote a user's type
    - Change a user's password

// COMMON FUNCTIONS
-------------------
This is a list of some commonly used functions

SANITIZE AN INPUT
    escape() (eg. escape($myinput))

GET AND SANITIZE GETS AND POSTS:
    Input:: (eg. Input::get('myid'))

CHECK IF USER HAS A PERMISSION
    $user->hasPermission() (eg. $user->hasPermission('manager'))
    // This uses a bubble up type structure in order to easily determine if a user has a certain permission or higher.
    // If a user has the 'manager' permission he'll by default have permission to access everything, but if a user has the 'sales' permission
    // he'll have access to everything except things that require a 'manager' permission, and so on.
    // If you need to do something specifically for only one permission type you can do it with and if($user->hasPermission('sales') && !$user->hasPermission('manager')) type statement
    // which would result in only users with the 'sales' permission.

GENERATE A SITE TOKEN
    Token::generate()

VALIDATE SITE TOKEN
    Token::check (eg. if(Token::check(Token::generate())) { *do stuff* })

GET USER DETAILS
    The userAccess class is extended by the userDetails class to access data from the user_details table instead of the user_access table
    $user = new userDetails(*email or id*);
    $data = $user->data();
    (eg. $data->city would return the user's city)

LOAD CONTENT IN AN IFRAME
    The template is set up to strip out the header and footer and replace the style.css with iframestyle.css if you load content through an iframe.
    This can be useful if you wish to load modal content through the framework instead of just calling inline or as a file.
    To do this simply add &view=iframe to the end of the url.
    (eg. href="index.php?option=tos&view=iframe)