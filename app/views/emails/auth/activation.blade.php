Hello {{ $username }},
<br>
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top" id="bodyCell">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody" style="background: none repeat scroll 0 0 #f5f8f8;">
                    <tr>
                        <td align="center" valign="top" style="background-color:#00334e;border-color:#080808;">
                            <img src="{{ URL::to('/'); }}/img/logo.png">
                            <span style="font-size: 16px;font-weight: bold; height: 50px;padding-top: 5px; color: #fff;">eshtihar<br>
                            <small style="font-size: 12px;font-weight: normal; color: #fff;">Pakistan's no.1 classified site</small></span>
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
                                                                Thanks for registering on eshtihar.com
                                                            <td/>
                                                        </tr>
                                                        <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                Please follow the link to Activate your Account.
                                                            <td/>
                                                        </tr> 
                                                       <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                {{ $link }}
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
