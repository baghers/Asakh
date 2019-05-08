<?php

/* 

//class/email.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/ret_pass1.php
 -
*/

class email //کلاس ارسال رایانامه
{
   function Sendemail($to,$subj,$mesg,$headers)
  	{
       if(mail($to, $subj, $mesg, $headers))
         return 1;
       else
         return 2;
	}
}
?>