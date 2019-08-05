# csci4140-assignment1
Assignment 1 for CSCI4140 Term 2 2018-2019

### Link:- https://csci4140-assignment1-ghous.herokuapp.com/
## Set up:-
1. Clone directory using the temrinal command:
    git clone https://github.com/ghousali17/csci4140-assignment1.git
2. Modify the include/dbh.php to according to the servername, username, password , database name of your sql server ( the corresponding 
    variables are $server, $username, $password, $db respectively).   
3. Start the SQL and PHP servers. (Make sure database doesn't contain tables named 'users' and 'gallery' )
4. Default username and passwords are:
   username:admin
   password:minda123
   username:Alice
   password:csci4140
   (Note: both username and passwords are case sensitive and the initialisation feature of admin account will delete all users except
   admin and Alice).
   
## Directory Tree:-
.
├── composer.json                                                    // this is the .json file to generate composer .lock file
├── composer.lock                                                    //.lock file genrate by composer
├── favicon.ico                                                      //favicon icon for the website
├── images                                                          //folder to store the images with permalinks
├── include               //folder containing php scripts
│   ├── authenticate.php                                //contains authenticate() function to authenticates a client's cookies
│   ├── dbh.php                                     //sql file to store databse parameters (must be modified on redployment)
│   ├── editor.php                                  //UI and php code for image editor
│   ├── filterhelper.php                            // contains functions used to implement filters
│   ├── initcomplete.php                            //UI signalling the completion of initialization process
│   ├── initform.php                                //Display box for confirmation of initialization process
│   ├── init.php                                    //PHP code for initialization process
│   ├── loginform.php                               //login UI
│   ├── login.php                                   //PHP script for authenticating user credentials
│   ├── logout.php                                  //PHP script for resetting tokens and cookies on logout
│   ├── navigate.php                                //code for gallery page navigation
│   ├── uploadcomplete.php                          //UI for displaying permalinks after completin of upload
│   └── upload.php                                  //PHP script for error handling and uploading images
├── index.php       
├── README.md
├── schema.sql                                     //Schema details for TA's reference ( Contain table: users and gallery)
├── style                                          //Directory containing the CSS code
│   ├── editor.css                                 //CSS styling for editor  
│   ├── index.css                                  //CSS styling for main index file
│   ├── initcomplete.css                           //CSS styling for initialization completion page
│   ├── initform.css                               //CSS styling for initialization confimration page
│   ├── loginform.css                              //CSS styling for login page
│   └── uploadcomplete.css                         //CSS styling for upload completion page
├── temp                                        //Directory for storing images being edited at the moment
├── tools                                       //Directory for storing bwgrad.png and lensflare.png used by filter functions
│   ├── bwgrad.png                              //.png file used by Black White filter
    └── lensflare.png                           //.png file used by Lensflare filter
    
    
# Description:-
## Authentication (Bonus consideration):-
The authentication mechnaism of the website is implemented through cookies. When a user attempts to login, his or her credentials are
matched against the credentials in our database. If the credentials are approved, two cookies are set: 'asg1' with the value username and 'asg1-key' with value md5(microtime().rand()). The value of 'asg1-key' is a randomly generated hash that makes it impossible for an 
attacker to guess the cookie value of an user. This hash value is stored in the user databse after login and every user request is 
first verified against this hash value. If a user logs out, this hash value is reset to null in the database. Also all queries are 
made through POST method, therefore offering a greater degree of security than GET method.

## Database:-
The webapp's database uses MySQL for querying. The databsae vendor is ClearDB. The databse contains two tables: users and gallery. users table contains the columns: u_id (auto-incremented primary key), u_name (username of the user), u_passwd (clear text password of the user) and u_token (hash generated on login). The table gallery contains the columns: imgId(auto-incremented primary key that helps in sorting the results), imgName (stores the true name of uploaded image), imgMode (stores the mode i.e public, private), imgOwner(stores the name of user that uploaded the image), imgFullName(the unique name given to every uploaded image to prevent overwriting) and imgOrder (an extra field to support alternate image ordering in future).

## Access Control and Pagination:-
As specified in the Database section, the table 'gallery' tracks the upload mode and the owner of the images such that the images displayed are either public images or private images of the logged in user ( the logged in user is verified according to mechanism described in authentication). As required 8 images are displayed per page. The pagination controls are only visible if more than 8 image
or one page worth of content is available for display.
    
## Upload:-
In order to ensure that the correct file types are uploaded, not only do we check for the file extension, but we also use the 
mime_content_type() function of PHP. The file extensions are matched with the actual file types determined by the  
mime_content_type() function, before allowing an image to be uploaded.
    
## Editor (Bonus consideration):-
The editor doesn't make use of the Imagick class but rather uses Imagick functions such as convert() and composite() through terminal. Uploaded files are first uploaded to the temp directory. The images currently being edited are stored here, if the user confirms the images, they are moved to images directory and permalinks are generated. We have requested bonus consideration for this part because my undo feature is fully functional and works on multiple filter i.e. you can use as many filters as you want and as many times are you want and will still be able to get the original image back through undo. The assignment requirement was one filter per image
our editor supports all 5 filters per image.
    
## Initialize:-
The initialise function cleans both the image and temp directory. Even though our editor ensures temp directory cleanup during runtime 
we have added this extra measure as safegaurd against memory wastage. The intialize function deletes all entries in the users
and gallery databse, except the entries for users 'admin' and 'Alice' in the user table. We have left these entries as the assignment specified these users are predefined. 


