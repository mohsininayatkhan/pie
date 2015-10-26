<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
        <tr>
            <td align="center" valign="top" id="bodyCell">
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody" style="background: none repeat scroll 0 0 #f5f8f8;">
                    <tr>
                        <td align="center" valign="top" style="background-color:#00334e;border-color:#080808;">
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
                                                            <td valign="top" class="textContent">
                                                                <strong>Sender Name</strong>
                                                            <td />
                                                            <td><strong>Report Type</strong></td>
                                                            <td><strong>Ad Link</strong></td>
                                                        </tr>
                                                       <tr>
                                                            <td valign="top" class="textContent">
                                                                {{ $sender_name }}
                                                            <td />
                                                            <td>{{ $type }}</td>
                                                            <td>{{ $ad_link }}</td>
                                                            
                                                        </tr>
                                                       <tr>
                                                           <td valign="top" class="textContent" colspan="3">
                                                                {{ $messagetxt }}
                                                            <td />
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
