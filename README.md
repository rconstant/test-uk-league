### Installation instructions

    composer update 
    bin/console doctrine:schema:update --dump-sql --force

### Development task instructions

We would like you to create a Symfony based RESTful API which is secured with JWT authentication. The RESTful API you will design and develop is for football leagues and teams. If you like use some team names from the UK Premier League! We would like you to create the following endpoints in the API.

1.  Get a list of football teams in given league
2.  Create a football team
3.  Replace all attributes of a football team
4.  Delete a football league

You can assume that a football league has the following properties:
-   ID
-   Name
 
And a football team has the following properties:
-   ID
-   Name
-   Strip
   
The data types you use for the fields we have provided are completely up to you. You should use either MySQL or SQLite as data storage along with the Doctrine ORM. We are not providing any data for you in this exercise so please feel free to create your own Doctrine Data Fixtures to allow you to complete the exercise.

Please see the following “must have” requirements:
1.  Please do not use FOSRestBundle, Lexik, Nelmio and Swagger. We want to see your knowledge level.
2.  Do not create a GUI.
3.  Do not use Twig Forms.
4.  Add PHPUnit tests.
5.  Provide a “clean” code by applying certain coding standards.
6.  Please use Symfony version 4+ and base it on “symfony/skeleton” edition.

Please make sure your understand the requirements before developing the application. Please make sure you pay attention should be paid to the following requirements in particular:

-   PHP 7
-   OOP design patterns
-   SOLID principles
-   RESTFul API design/development best practices
-   PSR coding standards
-   PHPUnit
-   Understanding of “Thin Controllers and Fat Models/Services” philosophy
-   Doctrine ORM