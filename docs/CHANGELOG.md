![logo](https://raw.githubusercontent.com/yafp/monoto/master/www/images/logo/monotoLogoBlack.png) changelog
==========

# Changelog
## monoto 4.0.0 (Quadrangulum)
###  ```Added```
* General
  * [#233] Implemented PHP7 compatibility
  * [#272] Added changelog to project
* Notes handling
  * [#278] Switched to server processing. No more page reloads needed while working with notes.
  * [#236] Added key-listener for ckeditor
* Access  
  * [#263] Added basic .htaccess to handle some common server errors with an error page
* Login screen
  * [#287] Notification mail when account gets locked
* Search
  * [#242] Highlight search string in DataTable
* Administration
  * [#288] Added option to reset failed_logins_in_a_row for an user account

###  ```Changed```
* Login screen
  * [#264] Redesign of the login screen
* Note creation
  * [#244] DataTable is now hidden while creating a new note
  * [#245] Added cancel button to abort note creation process
  * [#259] Note title while creation can now contain ? and ! and |
  * [#258] Fix for new note creation with already existing title
* Note editing
  * [#237] Save should reload current note. Related with [#278]
  * [#269] Save button is only enabled if either note-title or note-content was changed
  * [#270] Editor is only in read-write mode if a note is selected
  * [#271] Toolbar is now collapsible
  * [#241] Toolbar is not visible on load, but shown when editor gets focus.
  * [#255] Re-enabled elementpath in CKEditor (showing informations about html tags)
* Activity Log  
  * [#257] Added colors to event column
* Updated several libraries  
  * [#246] Updated jQuery (from 2.2.3 to 3.3.1)
  * [#247] Updated Bootstrap (from 3.3.5 to 4.2.1)
  * [#231] Updated CKEditor (from 4.4.7 to 4.11.3)
  * [#248] Updated FontAwesome (from 4.6.3 to 5.7.0)
  * [#253] Updated DataTables (from 1.10.5 to 1.10.18)
  * [#274] Updated noty (from 2.3.8 to 2.4.1)
* Languages
  * [#210] Most parts of UI are available in .en and .de
* Database
  * [#254] Changed collation on all tables from swedish_ci to utg8_general_ci
  * Table: m_users
    * [#252] Usernames can now be 64 chars long (previous: 32)
    * [#251] Removed column 'user_icon'
  * Table: m_notes
    * [#250] Remove column 'tags'
* Profile
    * [#279] Pages: Renamed MyMonoto to Profile
    * [#281] Improve password change function
    * [#282] Show password strength
* Keyboard
    * [#283] Keyboard-look - pimp page with css
* Setup
    * [#285] Improved password validation
* Admin
    * [#256] Show used versions of the most relevant JS frameworks.
    * [#286] Show php version
    * [#289] Delete user works only for non-admin accounts
    * [#292] User account table: highlight admin accounts
    * [#293] User account table: highlight existing failed logins
    * [#294] Use ajax for admin functions
* Misc
  * [#272] Footer: Added link to changelog
  * [#249] Footer: Added link to documentation
  * [#276] Navigation: Moved to external file
  * [#280] PHP/Session: Using sub-array for Session variables
  * [#284] Added .htaccess for some important folders
  * [#291] Localize datatable
  * [#290] Bootstrap select
  * [#296] Switch from bootstrap-select to select2

###  ```Removed```
* Notes
  * [#209] Removed old word count plugin from CKEditor
* Admin
  * [#261] Removed update check from admin section
  * [#275] Removed maintenance mode
* Profile
  * [#295] Removed statistics section
* Search
  * [#260] Removed unused button
* Misc
  * [#234] Removed broken timeout handler
  * [#240] Removed quotes
  * [#239] Removed random logout images
  * [#249] Removed keyboard listener for online help. Is now linked in footer.
  * [#243] Removed desktop notifications
  * [#277] Removed javascript jquery cookie usage. Related with [#278]

###  ```Fixed```
* [#262] Improved code for resizing and storing of editor height
* [#265] Fixed broken note import function (textfiles)
* [#268] Fixed an error in note-deletion via shortcut
* [#266] Fixed an access / security issue in admin-section
* [#267] Fixed a bug where noteID was not resetted while resetting the UI
* [#267] Fixed a bug where noteVersion was not resetted while resetting the UI


## monoto 3.2.0
###  ```Changed```
* Changed new note creation process
* Simplified UI


## monoto 3.1.0
###  ```Added```
* Switch to bootstrap
* Switch to FontAwesome

###  ```Changed```
* New UI
* Several other undocumented changes


## monoto 3.0.0
###  ```Changed```
* New UI
* Replaced cleditor with ckeditor
* Several other undocumented changes


## monoto 2.0.0
###  ```Added```
* Added cleditor for editing content of notes
* Added Multi-user support
* Added new admin section
* Added more stats
* Added install script
* added data cleaner/eraser
* added user/notes pie chart
* added change password function

###  ```Changed```
* New UI


## monoto 1.0.0
###  ```Added```
* First version of monoto








# Info
## Versioning

  ```
  MAJOR.MINOR.PATCH
  ```

* ```MAJOR``` version (incompatible API changes etc)
* ```MINOR``` version (adding functionality)
* ```PATCH``` version (bug fixes)

Versioning and Changelog started with version 4.


## Categories
* ```Added```: for new features
* ```Changed```: for changes in existing functionality.
* ```Deprecated```: for soon-to-be removed features.
* ```Removed```: for now removed features.
* ```Fixed```: for any bug fixes.
* ```Security```: in case of vulnerabilities.
