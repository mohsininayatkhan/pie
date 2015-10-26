Hello {{ $name }},
<br>
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top" id="bodyCell">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody" style="background: none repeat scroll 0 0 #f5f8f8;">
                    <tr>
                        <td align="center" valign="top" style="background-color:#00334e;border-color:#080808;">
                            <!--<img src="http://www.eshtihar.com/dev2/public/img/logo.png">-->
                            <img src="src="{{ URL::to('/'); }}/img/header.png">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="height:10px;">
                            
                        </td>
                    </tr>                    
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="flexibleContainer">
                                            <tr>
                                                <td align="center" valign="top" width="600" class="flexibleContainerCell">
                                                    <table border="0" cellpadding="5" cellspacing="5" width="100%">
                                                       <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                You have recieved a contact us message
                                                            <td/>
                                                        </tr>
                                                        <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                Name: {{ $name }} 
                                                            <td/>
                                                        </tr> 
                                                        <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                Email: {{ $email }} 
                                                            <td/>
                                                        </tr>
                                                        <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                Subject: {{ $subject }} 
                                                            <td/>
                                                        </tr>
                                                       <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                Message: {{ $msg }} 
                                                            <td/>
                                                        </tr>                                                     
                                                                                                            
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="height:10px;">
                            
                        </td>
                    </tr>                    
                    <tr>
                        <td align="center" style="background-color:#00334e;border-color:#080808; height: 30px; color: #fff;" >
                            <a herf="#" style="color: #fff;">Contact us</a> | <a herf="#" style="color: #fff;">About us</a> | <a herf="#" style="color: #fff;">Privacy Policy</a>
                        </td>
                    </tr>
                    
                    <!-- // MODULE ROW -->

                </table>
                <!-- // EMAIL CONTAINER -->
            </td>
        </tr>
    </table>
</center>
