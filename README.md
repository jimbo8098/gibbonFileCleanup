# gibbonFileCleanup
This module lists all files in the upload folder and check the usage in the database.  
NOTE: The list of checks in database is incomplete. You may add to the list in z-fileclean-ajax.php

## Instruction
Warning: Please backup data before using this tool.

1. Select the year and month to list files uploaded during the period of time
2. Wait for the list to process. Some folders might take longer depending on number of files.
3. Copy NA to clipboard.
4. Go to phpmyadmin -> select your database -> search -> paste into search -> Select all tables. -> Go  
 (Consider dividing the list in text editor to process as longer list would take a much longer time)
5. If search returns with result, you may add the table and column into the z-fileclean-ajax.php( under function searchDB )
6. Repeat again until there's no result in the search
7. Once you are sure that the list of NA is not attributed to any data, you may delete the files.
8. Through linux terminal/SSH, you may CD to the root installation of Gibbon and run RM with the list.  
eg: cd /var/www/;rm uploads/2017/04/1.docx uploads/2017/04/2.docx
