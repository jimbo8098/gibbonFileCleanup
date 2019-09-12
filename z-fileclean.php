<?php
/*
 * setup basic data to allow reports to be written
 */


if (isActionAccessible($guid, $connection2,"/modules/File Cleanup/z-fileclean.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
    print "You do not have access to this action." ;
    print "</div>" ;
} else {
    echo "<h3>File List</h3>";
    if(isset($_GET['path'])){
        if(strpos($_GET['path'],"..")!== false) {
            print "<div class='error'>";
            print "Something went wrong. Please contact system admin.";
            print "</div>";
            exit();
        }
        echo "<button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='backToList()'>Back to Folder List</button>&emsp;";
        echo "<button class='actionBtn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='hideNA()'>Toggle NA</button>&emsp;";
        echo "<button class='actionBtn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='cpNA()'>Copy NA to clipboard</button>&emsp;";
        echo "<button class='actionBtn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='hideDone()'>Toggle not-NA</button><br><br>";
        echo "<span class='loading lds-dual-ring'></span>";
        
        //Set the module URL in module.js
        echo "<script type='text/javascript'>ajaxUrl = '" . $_SESSION[$guid]['absoluteURL'] . "/modules/" . $_SESSION[$guid]['module'] . "/z-fileclean-ajax.php';</script>";
        arrayFilter(dirToArray("uploads/".$_GET['path']),$connection2);
    }else{
        ?>
        <p class="text-red-700 font-bold">Warning: Please backup data before using this tool.</p>
        <ul class="list-decimal">
            <li>Select the year and month to list files uploaded during the period of time</li>
            <li>Wait for the list to process. Some folder might take longer depending on number of files.</li>
            <li>Copy NA to clipboard.<br></li>
            <li>Go to phpmyadmin -> select your database -> search -> paste into search -> Select all tables. -> Go<br>
                (Consider dividing the list in text editor to process as longer list would take a much longer time)</li>
            <li>If search returns with result, you may add the table and column into the z-fileclean-ajax.php( under function searchDB )</li>
            <li>Repeat again until there's no result in the search</li>
            <li>Once you are sure that the list of NA is not attributed to any data, you may delete the files.</li>
            <li>Through linux terminal/SSH, you may CD to the root installation of Gibbon and run RM with the list.<br>
                eg: cd /var/www/;rm uploads/2017/04/1.docx uploads/2017/04/2.docx</li>
        </ul><br>
        <?php
        listDirectory(dirToArray('uploads',1));
    }


}

function dirToArray($dir,$level=2) {

    $result = array();

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value)
    {
        if (!in_array($value,array(".","..",".htaccess","cache")))
        {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value) && $level>0)
            {
                    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value,$level-1);
            }
            else
            {
                $result[] = $value;
            }
        }
    }

    return $result;
}

function listDirectory($arr){
    if(sizeof($arr) > 0)
    {
        echo "<style>ul.listd>li>ul>li{display:inline-block;width:50px;}</style>";
        echo "<ul class='listd'>";
        foreach($arr as $ykey=>$year) {
            echo "<li>$ykey<ul>";
            foreach ($year as $mkey => $month) {
                $fp = $ykey . "/" . $month;
                echo "<li><button class='rounded py-2 px-4' onclick='window.location+=\"&path=$fp\"'>$month</button></li>";

            }
            echo "</ul></li>";
        }
        echo "</ul>";
    }
    else
    {
        echo "No years found";
    }
}

function arrayFilter($arr,$dbh){
    echo "<table>";
    foreach($arr as $key=>$file){
        $fp = "uploads/" . $_GET["path"] . "/" . $file;
        echo "<tr>";
        echo "<td>";
        echo $fp;
        echo "</td>";
        echo "<td class='chkFile'>";
        echo "Loading";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}


?>