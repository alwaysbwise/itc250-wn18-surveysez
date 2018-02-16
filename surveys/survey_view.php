<?php
/**
 * survey_view.php along with index.php provides a List/View application for the 
 * SurveySez project
 * 
 * @package SurveySez
 * @author Brian Wise <briandwise7@gmail.com>
 * @version 0.1 2018/02/08
 * @link http://www.brianwise.xyz/wn18
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see survey_list.php
 * @todo none
 */
# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
# without this if block querystring hacks are allowed 
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
}

$mySurvey = new Survey($myID);

//dumpDie($mySurvey);

//---end config area --------------------------------------------------

if($mySurvey->IsValid)
{#only load data if record found
	$config->titleTag = $mySurvey->Title;
}

# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>

<?php
if($mySurvey->IsValid)
{#records exist - show muffin!
    echo'
    <h3 align="center">' . $mySurvey->Title . '</h3>
    <p>Description: ' . $mySurvey->Description . '</p>
    <p>Date Added: ' . $mySurvey->DateAdded . '</p>
    ';
}else{//no such survey!
    
    echo '
    <p>There is no such survey</p>
    ';
}
get_footer(); #defaults to theme footer or footer_inc.php

class Survey
{
    //class variables
    public $SurveyID = 0;
    public $Title = '';
    public $Description = '';
    public $DateAdded = '';
    public $IsValid = false;
    
    public function __construct($myID)
    {
        //cast the data to an integer to protect the class integrity
        $this->SurveyID = (int)$myID;
        
        $sql = "select Title, Description,DateAdded from wn18_surveys where SurveyID = " . $this->SurveyID;
        
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
        {#records exist - process
               $this->IsValid = true;	
               while ($row = mysqli_fetch_assoc($result))
               {
                    $this->Title = dbOut($row['Title']);
                    $this->Description = dbOut($row['Description']);
                    $this->DateAdded = dbOut($row['DateAdded']);			
                   
               }//end while 
        }//end if

        @mysqli_free_result($result); # We're done with the data!
        
        
    }//end Survey constructor
}//end Survey class


class Question
{
    
    //class variables
    public $QuestionID = 0;
    public $QuestionText = '';
    public $Description = '';
    
    public function __construct($QuestionID, $QuestionText, $Description)
    {
        $this->QuestionID = $QuestionID;
        $this->QuestionText = $QuestionText;
        $this->Description = $Description;
        
        
    }//end Question constructor   
}//end Question class

