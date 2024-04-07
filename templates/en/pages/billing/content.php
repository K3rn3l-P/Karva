<?php $assetPath = "templates/en/pages/billing/";

   $serverURL = "http://100.112.150.44:8080";
   $_SESSION['paymentStep'] = 1;
  

//Have youi comment this line  i don't remember to do something 
   //include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php"); ?>
   
  
   
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>SP Payment</title>
      <!-- CSS -->
      <link
         rel="stylesheet"
         href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500"
         />
      <link rel="stylesheet" href="<?= $assetPath ?>assets/bootstrap/css/bootstrap.min.css" />
      <link
         rel="stylesheet"
         href="<?= $assetPath ?>assets/font-awesome/css/font-awesome.min.css"
         />
      <link rel="stylesheet" href="<?= $assetPath ?>assets/css/form-elements.css" />
      <link rel="stylesheet" href="<?= $assetPath ?>assets/css/style.css" />
      <!-- Favicon and touch icons -->
      <link rel="shortcut icon" href="<?= $assetPath ?>assets/ico/favicon.ico" /> 
      <link
         rel="apple-touch-icon-precomposed"
         sizes="144x144"
         href="<?= $assetPath ?>assets/ico/apple-touch-icon-144-precomposed.png"
         />
      <link
         rel="apple-touch-icon-precomposed"
         sizes="114x114"
         href="<?= $assetPath ?>assets/ico/apple-touch-icon-114-precomposed.png"
         />
      <link
         rel="apple-touch-icon-precomposed"
         sizes="72x72"
         href="<?= $assetPath ?>assets/ico/apple-touch-icon-72-precomposed.png"
         />
      <link
         rel="apple-touch-icon-precomposed"
         href="<?= $assetPath ?>assets/ico/apple-touch-icon-57-precomposed.png"
         />
   </head>
   <body>
      <div class="top-content">
         <div class="container">
            <div class="row">
               <div
                  class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 form-box"
                  >
                  <form class="f1" method="post">
                     <h4>SP Currency Purchasing</h4>
                     <div
                        id="alert-error"
                        class="alert alert-danger"
                        role="alert"
                        style="display: none"
                        >
                        Please enter complete card details
                        <a
                           href="#"
                           class="close"
                           data-dismiss="alert"
                           aria-label="close"
                           >&times;</a
                           >
                     </div>
                     <div class="f1-steps">
                        <div class="f1-progress">
                           <div
                              class="f1-progress-line"
                              data-now-value="20"
                              data-number-of-steps="5"
                              style="width: 20%"
                              ></div>
                        </div>
                        <div class="f1-step active" id="TermHeading">
                           <div class="f1-step-icon">
                              <i class="fa fa-certificate"></i>
                           </div>
                           <p>Step-1 Payment Terms</p>
                        </div>
                        <div class="f1-step" id="TermPaymentMethod">
                           <div class="f1-step-icon">
                              <i class="fa fa-credit-card"></i>
                           </div>
                           <p>Step-2 Payment Method</p>
                        </div>
                        <div class="f1-step">
                           <div class="f1-step-icon"><i class="fa fa-money"></i></div>
                           <p>Step-3 SP Amount €</p>
                        </div>
                        <div class="f1-step" id="BIHeading">
                           <div class="f1-step-icon">
                              <i class="fa fa-info-circle"></i>
                           </div>
                           <p>Step-4 Contact Information</p>
                        </div>
                        <div class="f1-step" id="ConfirmationHeading">
                           <div class="f1-step-icon"><i class="fa fa-random"></i></div>
                           <p>Step-5 Confirmation</p>
                        </div>
                     </div>
                     <fieldset id="termFiedlset">
                        <div class="alert alert-danger" role="alert" id="alert-term" style="display: none">
                           You must accept terms and conditions.
                        </div>
                        <h4>Payment Terms</h4>
                        <div class="col-md-8 col-md-offset-2">
                           <div class="form-check form-check-inline">
                              <input id="termscheckbox" type="checkbox" >
                              <label for="termscheckbox">I'm agree with 
                              <a href="payment-terms.html" target="_blank" onclick="window.open(this.href, 'mywin',
                                 'left=500,top=300,width=500,height=500,toolbar=1,resizable=0'); return false;" >terms and conditions</a>.
                              </label>
                           </div>
                           <div class="f1-buttons">
                              <button type="button" id="btn-terms" class="btn btn-general">Next</button>
                           </div>
                        </div>
                     </fieldset>
                     <fieldset id="paymentMethodFiedlset">
                        <h4>Payment Method</h4>
                        <div class="col-md-8 col-md-offset-2">
                          
						 <div class="form-check">
                              <input
                                 class="form-check-input"
                                 type="radio"
                                 name="payment-method"
                                 id="radio-paypal"
                                 value="paypal" 
                                 />
                              <label class="form-check-label" for="radio-paypal">
                              <strong>Purchase through PayPal [recommended]</strong>
                              </label>
                           </div>
						   	

						  <div class="form-check">
                              <input
                                 class="form-check-input"
                                 type="radio"
                                 name="payment-method"
                                 id="radio-stripe"
                                 value="stripe" 
                                 checked
                                 />
                              <label class="form-check-label" for="radio-stripe">
                              <strong>Purchase through Stripe </strong>
                              </label>
                           </div>

						   
						  
                         



						





						 <div class="form-check">
                              <input
                                 class="form-check-input"
                                 type="radio"
                                 name="payment-method"
                                 id="radio-paymentwall"
                                 value="paymentwall" 
                                 />
                              <label class="form-check-label" for="radio-paymentwall">
                              <strong>Purchase through PaymentWall</strong>
                              </label>
                           </div>
						   
						   
                           
						   
						   
						   
                           <input
                              type="hidden"
                              id="uid"
                              name="uid"
                              value="<?= $UserUID ?>"
                              />
                           <div class="f1-buttons">
                              <button type="button" class="btn btn-previous">
                              Previous
                              </button>
                              <button type="button"  id="btn-payment-method" class="btn btn-primary">Next</button>
                           </div>
                        </div>
                     </fieldset>
                     <fieldset>
                        <h4>Payment Amount:</h4>
                        <div class="col-md-8 col-md-offset-2">
                           <iframe src="https://api.paymentwall.com/api/ps/?key=d898021769f1a225d6695d815c9ee5b5&uid=<?= $UserUID ?>&email=<?= $UserInfo["Email"] ?>&widget=p10_1"
                              width="700" height="800" frameborder="0" id="paymentwall-iframe"></iframe>
                           
						   
						   
						   
						   <table class="table" id="paypalPackagesTable">
                              <thead>
                                 <tr>
                                    <th scope="col">Select</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Total</th>
                                 </tr>
                              </thead>
                              <tbody>
							  
							  
							 
							 
							 
							 
								<tr>
                                    <td data-label="select">
                                       <input
                                          class="paypal-radio"
                                          type="radio"
                                          name="paypal_price"
                                          value="10.00"
                                          checked
                                          />
                                    </td>
                                    <td data-label="price">10 €</td>
                                    <td data-label="sp">1000 SP</td>
                                    <td>1000 SP</td>
                                 </tr>
							 
							 
							 
							 
							 
							 
							 
							  
                                
								 
								 
								 
								 
								 
                                 <tr>                                   
                                    <td data-label="select">
                                       <input
                                          class="paypal-radio"
                                          type="radio"
                                          name="paypal_price"
                                          value="25.00"
                                          />
                                    </td>
                                    <td data-label="price">25 €</td>
                                    <td data-label="sp">2500 SP + 250 Bonus (Save 10%)</td>
                                    <td>2750 SP</td>
                                 </tr>
								 
								 
								 
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          class="paypal-radio"
                                          type="radio"
                                          name="paypal_price"
                                          value="50.00"
                                          />
                                    </td>
                                    <td data-label="price">50 €</td>
                                    <td data-label="sp">5000 SP + 1000 Bonus (Save 20%)</td>
                                    <td>6000 SP</td>
                                 </tr>
								 
								 
                                 
								 <tr>
                                    <td data-label="select">
                                       <input
                                          class="paypal-radio"
                                          type="radio"
                                          name="paypal_price"
                                          value="100.00"
                                          />
                                    </td>
                                    <td data-label="price">100 €</td>
                                    <td data-label="sp">10.000 SP + 3000 Bonus (Save 30%)</td>
                                    <td>13.000 SP</td>
                                 </tr>
								 
								  
								 
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          class="paypal-radio"
                                          type="radio"
                                          name="paypal_price"
                                          value="150.00"
                                          />
                                    </td>
                                    <td data-label="price">200 €</td>
                                    <td data-label="sp">20.000 SP + 8000 Bonus (Save 40%)</td>
                                    <td>28.000 SP</td>
                                 </tr>
								 
								 
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          class="paypal-radio"
                                          type="radio"
                                          name="paypal_price"
                                          value="200.00"
                                          />
                                    </td>
                                    <td data-label="price">300 €</td>
                                    <td data-label="sp">30.000 SP + 15.000 Bonus (Save 50%)</td>
                                    <td>45.000 SP</td>
                                 </tr>
                                
                                 
                              </tbody>
                           </table>
						   
						   
						   
                           <table class="table" id="stripePackagesTable">
                              <thead>
                                 <tr>
                                    <th scope="col">Select</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Total</th>
                                 </tr>
                              </thead>
                              <tbody>
							  
                               
								
								
								<tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price5"
                                          name="price"
                                          value="5"
                                          checked
                                          />
									</td>
                                    <td data-label="price">5 €</td>
                                    <td data-label="sp">500 SP</td>
                                    <td>500 SP</td>
                                </tr>
								 
								 
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price10"
                                          name="price"
                                          value="10"
                                          />
                                    </td>
                                    <td data-label="price">10 €</td>
                                    <td data-label="sp">1000 + 100 Bonus (Save 10%)</td>
                                    <td>1100 SP</td>
                                 </tr>
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price25"
                                          name="price"
                                          value="25"
                                          />
                                    </td>
                                    <td data-label="price">25 €</td>
                                    <td data-label="sp">2500 + 500 Bonus (Save 25%)</td>
                                    <td>3000 SP</td>
                                 </tr>
                                
								<!-- 

								<tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price50"
                                          name="price"
                                          value="50"
                                          />
                                    </td>
                                    <td data-label="price">50 €</td>
                                    <td data-label="sp">5000 + 1500 Bonus (Save 30%)</td>
                                    <td>6500 SP</td>
                                 </tr>
								 
								 
								    
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price100"
                                          name="price"
                                          value="100"
                                          />
                                    </td>
                                    <td data-label="price">100 €</td>
                                    <td data-label="sp">10.000 + 3000 Bonus (Save 30%)</td>
                                    <td>13.000 SP</td>
                                 </tr>
								 
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price150"
                                          name="price"
                                          value="150"
                                          />
                                    </td>
                                    <td data-label="price">150 €</td>
                                    <td data-label="sp">15.000 + 6000 Bonus (Save 40%)</td>
                                    <td>21.000 SP</td>
                                 </tr>
                                 <tr>
                                    <td data-label="select">
                                       <input
                                          type="radio"
                                          id="price200"
                                          name="price"
                                          value="200"
                                          />
                                    </td>
                                    <td data-label="price">200 €</td>
                                    <td data-label="sp">20.000 + 10.000 Bonus (Save 50%)</td>
                                    <td>30.000 SP</td>
                                 </tr>
								  -->
								  
								  
                              </tbody>
                           </table>

                           <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                      
                                    </button>
                                    <h4 class="modal-title" id="exampleModalLabel"><strong>IMPORTANT TO READ
                                    
                                       </strong></h4>
                                   
                                    </div>
                                    <div class="modal-body">
                                    
									<p><span aria-hidden="true" style="color:red;"> &#9888;</span>   Please do not close the PayPal window after donation is complete, wait for PayPal to redirect back to our website <b>WWW.SHAIYA DUFF.COM</b></p>
                                    <p><span aria-hidden="true" style="color:red;"> &#9888;</span> In case you didn't received SP after donation is complete <b><a href="./?p=contact" target="_blank">Contact Us</a></b> providing the following information:</br>
									
&nbsp;&nbsp;&nbsp; -PayPal Email;</br>
&nbsp;&nbsp;&nbsp; -PayPal Amount € Donated;</br>
&nbsp;&nbsp;&nbsp; -Game Account Name;</br>
&nbsp;&nbsp;&nbsp; -Game Main Character Name;</p>
									
									<br>	


                                   <p><i>Thanks for reading and for supporting our community!</i></p>
                                    </div>
									
									
									
									
                                    <div class="modal-footer">
                                    <input class="btn btn-primary" style="margin-bottom: 5px; padding-top: 8px;padding-bottom: 8px;padding-right: 25px;padding-left: 25px;"  type="submit" form="paypal-form" /> 
                               
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <!-- data-toggle="modal" data-target="#exampleModal" -->

                           <div class="f1-buttons" >
                              <button type="button" class="btn btn-previous">
                              Previous
                              </button>
                              <!-- <button class="btn btn-primary" id="btn-paypal-packages">Confirm</button> -->
                              <button type="button" class="btn btn-primary" id="btn-paypal-packages"  data-toggle="modal" data-target="#exampleModal">
                              Submit
                              </button>
                              <!-- <input class="btn btn-primary" id="btn-paypal-packages" style="margin-bottom: 5px; padding-top: 8px;padding-bottom: 8px;padding-right: 25px;padding-left: 25px;"  type="submit" form="paypal-form" /> -->
                              <a id="btn-paypal-href" style="color: #FFF; display:none;"  href="https://www.paypal.com/donate?hosted_button_id=WZTDHMD9DH28C" role="button" >Confirm</a>
                              <button type="button" id="btn-stripe-packages" class="btn btn-next">Next</button>
                           </div>
						   
						   
						   
						   
                        </div>
                     </fieldset>
                     <fieldset id="bIFiedlset">
                        <div
                           id="alert-contact"
                           class="alert alert-danger"
                           role="alert"
                           style="display: none"
                           >
                           <a
                              href="#"
                              class="close"
                              data-dismiss="alert"
                              aria-label="close"
                              >&times;</a
                              >
                        </div>
                        <h4>Contact Information:</h4>
                        <div id="paymentResponse" style="color: red"></div>
                        <div class="col-md-8 col-md-offset-2">
                           <div class="form-group">
                              <select
                                 id="f1-country"
                                 name="f1-country"
                                 required
                                 class="form-control f1-country"
                                 >
                                 <option value="" selected>Select a country</option>
                                 <option value="AF">Afghanistan</option>
                                 <option value="AL">Albania</option>
                                 <option value="DZ">Algeria</option>
                                 <option value="AS">American Samoa</option>
                                 <option value="AD">Andorra</option>
                                 <option value="AO">Angola</option>
                                 <option value="AI">Anguilla</option>
                                 <option value="AQ">Antarctica</option>
                                 <option value="AG">Antigua and Barbuda</option>
                                 <option value="AR">Argentina</option>
                                 <option value="AM">Armenia</option>
                                 <option value="AW">Aruba</option>
                                 <option value="AU">Australia</option>
                                 <option value="AT">Austria</option>
                                 <option value="AZ">Azerbaijan</option>
                                 <option value="BS">Bahamas</option>
                                 <option value="BH">Bahrain</option>
                                 <option value="BD">Bangladesh</option>
                                 <option value="BB">Barbados</option>
                                 <option value="BY">Belarus</option>
                                 <option value="BE">Belgium</option>
                                 <option value="BZ">Belize</option>
                                 <option value="BJ">Benin</option>
                                 <option value="BM">Bermuda</option>
                                 <option value="BT">Bhutan</option>
                                 <option value="BO">Bolivia</option>
                                 <option value="BA">Bosnia and Herzegowina</option>
                                 <option value="BW">Botswana</option>
                                 <option value="BV">Bouvet Island</option>
                                 <option value="BR">Brazil</option>
                                 <option value="IO">British Indian Ocean Territory</option>
                                 <option value="BN">Brunei Darussalam</option>
                                 <option value="BG">Bulgaria</option>
                                 <option value="BF">Burkina Faso</option>
                                 <option value="BI">Burundi</option>
                                 <option value="KH">Cambodia</option>
                                 <option value="CM">Cameroon</option>
                                 <option value="CA">Canada</option>
                                 <option value="CV">Cape Verde</option>
                                 <option value="KY">Cayman Islands</option>
                                 <option value="CF">Central African Republic</option>
                                 <option value="TD">Chad</option>
                                 <option value="CL">Chile</option>
                                 <option value="CN">China</option>
                                 <option value="CX">Christmas Island</option>
                                 <option value="CC">Cocos (Keeling) Islands</option>
                                 <option value="CO">Colombia</option>
                                 <option value="KM">Comoros</option>
                                 <option value="CG">Congo</option>
                                 <option value="CD">
                                    Congo, the Democratic Republic of the
                                 </option>
                                 <option value="CK">Cook Islands</option>
                                 <option value="CR">Costa Rica</option>
                                 <option value="CI">Cote d'Ivoire</option>
                                 <option value="HR">Croatia (Hrvatska)</option>
                                 <option value="CU">Cuba</option>
                                 <option value="CY">Cyprus</option>
                                 <option value="CZ">Czech Republic</option>
                                 <option value="DK">Denmark</option>
                                 <option value="DJ">Djibouti</option>
                                 <option value="DM">Dominica</option>
                                 <option value="DO">Dominican Republic</option>
                                 <option value="TP">East Timor</option>
                                 <option value="EC">Ecuador</option>
                                 <option value="EG">Egypt</option>
                                 <option value="SV">El Salvador</option>
                                 <option value="GQ">Equatorial Guinea</option>
                                 <option value="ER">Eritrea</option>
                                 <option value="EE">Estonia</option>
                                 <option value="ET">Ethiopia</option>
                                 <option value="FK">Falkland Islands (Malvinas)</option>
                                 <option value="FO">Faroe Islands</option>
                                 <option value="FJ">Fiji</option>
                                 <option value="FI">Finland</option>
                                 <option value="FR">France</option>
                                 <option value="FX">France, Metropolitan</option>
                                 <option value="GF">French Guiana</option>
                                 <option value="PF">French Polynesia</option>
                                 <option value="TF">French Southern Territories</option>
                                 <option value="GA">Gabon</option>
                                 <option value="GM">Gambia</option>
                                 <option value="GE">Georgia</option>
                                 <option value="DE">Germany</option>
                                 <option value="GH">Ghana</option>
                                 <option value="GI">Gibraltar</option>
                                 <option value="GR">Greece</option>
                                 <option value="GL">Greenland</option>
                                 <option value="GD">Grenada</option>
                                 <option value="GP">Guadeloupe</option>
                                 <option value="GU">Guam</option>
                                 <option value="GT">Guatemala</option>
                                 <option value="GN">Guinea</option>
                                 <option value="GW">Guinea-Bissau</option>
                                 <option value="GY">Guyana</option>
                                 <option value="HT">Haiti</option>
                                 <option value="HM">Heard and Mc Donald Islands</option>
                                 <option value="VA">Holy See (Vatican City State)</option>
                                 <option value="HN">Honduras</option>
                                 <option value="HK">Hong Kong</option>
                                 <option value="HU">Hungary</option>
                                 <option value="IS">Iceland</option>
                                 <option value="IN">India</option>
                                 <option value="ID">Indonesia</option>
                                 <option value="IR">Iran (Islamic Republic of)</option>
                                 <option value="IQ">Iraq</option>
                                 <option value="IE">Ireland</option>
                                 <option value="IL">Israel</option>
                                 <option value="IT">Italy</option>
                                 <option value="JM">Jamaica</option>
                                 <option value="JP">Japan</option>
                                 <option value="JO">Jordan</option>
                                 <option value="KZ">Kazakhstan</option>
                                 <option value="KE">Kenya</option>
                                 <option value="KI">Kiribati</option>
                                 <option value="KP">
                                    Korea, Democratic People's Republic of
                                 </option>
                                 <option value="KR">Korea, Republic of</option>
                                 <option value="KW">Kuwait</option>
                                 <option value="KG">Kyrgyzstan</option>
                                 <option value="LA">
                                    Lao People's Democratic Republic
                                 </option>
                                 <option value="LV">Latvia</option>
                                 <option value="LB">Lebanon</option>
                                 <option value="LS">Lesotho</option>
                                 <option value="LR">Liberia</option>
                                 <option value="LY">Libyan Arab Jamahiriya</option>
                                 <option value="LI">Liechtenstein</option>
                                 <option value="LT">Lithuania</option>
                                 <option value="LU">Luxembourg</option>
                                 <option value="MO">Macau</option>
                                 <option value="MK">
                                    Macedonia, The Former Yugoslav Republic of
                                 </option>
                                 <option value="MG">Madagascar</option>
                                 <option value="MW">Malawi</option>
                                 <option value="MY">Malaysia</option>
                                 <option value="MV">Maldives</option>
                                 <option value="ML">Mali</option>
                                 <option value="MT">Malta</option>
                                 <option value="MH">Marshall Islands</option>
                                 <option value="MQ">Martinique</option>
                                 <option value="MR">Mauritania</option>
                                 <option value="MU">Mauritius</option>
                                 <option value="YT">Mayotte</option>
                                 <option value="MX">Mexico</option>
                                 <option value="FM">
                                    Micronesia, Federated States of
                                 </option>
                                 <option value="MD">Moldova, Republic of</option>
                                 <option value="MC">Monaco</option>
                                 <option value="MN">Mongolia</option>
                                 <option value="MS">Montserrat</option>
                                 <option value="MA">Morocco</option>
                                 <option value="MZ">Mozambique</option>
                                 <option value="MM">Myanmar</option>
                                 <option value="NA">Namibia</option>
                                 <option value="NR">Nauru</option>
                                 <option value="NP">Nepal</option>
                                 <option value="NL">Netherlands</option>
                                 <option value="AN">Netherlands Antilles</option>
                                 <option value="NC">New Caledonia</option>
                                 <option value="NZ">New Zealand</option>
                                 <option value="NI">Nicaragua</option>
                                 <option value="NE">Niger</option>
                                 <option value="NG">Nigeria</option>
                                 <option value="NU">Niue</option>
                                 <option value="NF">Norfolk Island</option>
                                 <option value="MP">Northern Mariana Islands</option>
                                 <option value="NO">Norway</option>
                                 <option value="OM">Oman</option>
                                 <option value="PK">Pakistan</option>
                                 <option value="PW">Palau</option>
                                 <option value="PA">Panama</option>
                                 <option value="PG">Papua New Guinea</option>
                                 <option value="PY">Paraguay</option>
                                 <option value="PE">Peru</option>
                                 <option value="PH">Philippines</option>
                                 <option value="PN">Pitcairn</option>
                                 <option value="PL">Poland</option>
                                 <option value="PT">Portugal</option>
                                 <option value="PR">Puerto Rico</option>
                                 <option value="QA">Qatar</option>
                                 <option value="RE">Reunion</option>
                                 <option value="RO">Romania</option>
                                 <option value="RU">Russian Federation</option>
                                 <option value="RW">Rwanda</option>
                                 <option value="KN">Saint Kitts and Nevis</option>
                                 <option value="LC">Saint LUCIA</option>
                                 <option value="VC">
                                    Saint Vincent and the Grenadines
                                 </option>
                                 <option value="WS">Samoa</option>
                                 <option value="SM">San Marino</option>
                                 <option value="ST">Sao Tome and Principe</option>
                                 <option value="SA">Saudi Arabia</option>
                                 <option value="SN">Senegal</option>
                                 <option value="SC">Seychelles</option>
                                 <option value="SL">Sierra Leone</option>
                                 <option value="SG">Singapore</option>
                                 <option value="SK">Slovakia (Slovak Republic)</option>
                                 <option value="SI">Slovenia</option>
                                 <option value="SB">Solomon Islands</option>
                                 <option value="SO">Somalia</option>
                                 <option value="ZA">South Africa</option>
                                 <option value="GS">
                                    South Georgia and the South Sandwich Islands
                                 </option>
                                 <option value="ES">Spain</option>
                                 <option value="LK">Sri Lanka</option>
                                 <option value="SH">St. Helena</option>
                                 <option value="PM">St. Pierre and Miquelon</option>
                                 <option value="SD">Sudan</option>
                                 <option value="SR">Suriname</option>
                                 <option value="SJ">Svalbard and Jan Mayen Islands</option>
                                 <option value="SZ">Swaziland</option>
                                 <option value="SE">Sweden</option>
                                 <option value="CH">Switzerland</option>
                                 <option value="SY">Syrian Arab Republic</option>
                                 <option value="TW">Taiwan, Province of China</option>
                                 <option value="TJ">Tajikistan</option>
                                 <option value="TZ">Tanzania, United Republic of</option>
                                 <option value="TH">Thailand</option>
                                 <option value="TG">Togo</option>
                                 <option value="TK">Tokelau</option>
                                 <option value="TO">Tonga</option>
                                 <option value="TT">Trinidad and Tobago</option>
                                 <option value="TN">Tunisia</option>
                                 <option value="TR">Turkey</option>
                                 <option value="TM">Turkmenistan</option>
                                 <option value="TC">Turks and Caicos Islands</option>
                                 <option value="TV">Tuvalu</option>
                                 <option value="UG">Uganda</option>
                                 <option value="UA">Ukraine</option>
                                 <option value="AE">United Arab Emirates</option>
                                 <option value="GB">United Kingdom</option>
                                 <option value="US">United States</option>
                                 <option value="UM">
                                    United States Minor Outlying Islands
                                 </option>
                                 <option value="UY">Uruguay</option>
                                 <option value="UZ">Uzbekistan</option>
                                 <option value="VU">Vanuatu</option>
                                 <option value="VE">Venezuela</option>
                                 <option value="VN">Viet Nam</option>
                                 <option value="VG">Virgin Islands (British)</option>
                                 <option value="VI">Virgin Islands (U.S.)</option>
                                 <option value="WF">Wallis and Futuna Islands</option>
                                 <option value="EH">Western Sahara</option>
                                 <option value="YE">Yemen</option>
                                 <option value="YU">Yugoslavia</option>
                                 <option value="ZM">Zambia</option>
                                 <option value="ZW">Zimbabwe</option>
                              </select>
                           </div>
                           <!-- Stripe Card Field --> 
                           <div class="col-md-12 form-group" style="padding:0%;">
                              <div id="card-element" class="form-control col-md-12">
                                 <!--Stripe.js injects the Card Element-->
                              </div>
                           </div>
                           <p id="card-error" role="alert"></p>
                           <div class="form-group col-md-6 control-margin">
                              <input
                                 type="text"
                                 name="f1-first-name"
                                 placeholder="First Name"
                                 maxlength="30"
                                 class="f1-first-name form-control"
                                 id="f1-first-name"
                                 required
                                 />
                           </div>
                           <div
                              class="form-group col-md-6"
                              style="padding-right: 0%; padding-left: 0%"
                              >
                              <input
                                 type="text"
                                 name="f1-last-name"
                                 maxlength="30"
                                 placeholder="Last Name"
                                 class="f1-last-name form-control"
                                 id="f1-last-name"
                                 />
                           </div>
                           <h4>Billing Address</h4>
                           <div class="form-group">
                              <input
                                 type="text"
                                 name="f1-address"
                                 placeholder="Address 1"
                                 maxlength="70"
                                 class="f1-address form-control"
                                 id="f1-address"
                                 />
                           </div>
                           <div class="form-group">
                              <input
                                 type="text"
                                 name="f1-sector"
                                 placeholder="Address 2"
                                 maxlength="45"
                                 class="f1-sector form-control"
                                 id="f1-sector"
                                 />
                           </div>
                           <div class="form-group col-md-6 control-margin">
                              <input
                                 type="text"
                                 name="f1-postal-code"
                                 placeholder="Postal Code"
                                 maxlength="10"
                                 class="f1-postal-code form-control"
                                 id="f1-postal-code"
                                 />
                           </div>
                           <div
                              class="form-group col-md-6"
                              style="padding-right: 0%; padding-left: 0%"
                              >
                              <input
                                 type="text"
                                 name="f1-city"
                                 placeholder="City"
                                 maxlength="30"
                                 class="f1-city form-control"
                                 id="f1-city"
                                 />
                           </div>
                           <h4>Comtact Information</h4>
                           <div class="form-group col-md-6 control-margin">
                              <select
                                 id="f1-phone-type"
                                 name="f1-phone-type"
                                 required
                                 class="form-control f1-phone-type"
                                 >
                                 <option value="" >Phone Type</option>
                                 <option value="Mobile" selected>Mobile</option>
                                 <option value="Work">Work</option>
                                 <option value="Home">Home</option>
                              </select>
                           </div>
                           <div
                              class="form-group col-md-6"
                              style="padding-right: 0%; padding-left: 0%"
                              >
                              <input
                                 type="text"
                                 name="f1-phone"
                                 placeholder="Phone Number"
                                 maxlength="15"
                                 class="f1-phone form-control"
                                 id="f1-phone"
                                 />
                           </div>
                           <div class="form-group">
                              <input
                                 type="email"
                                 name="f1-email"
                                 maxlength="45"
                                 placeholder="Email"
                                 class="f1-email form-control"
                                 id="f1-email"
                                 />
                           </div>
                           <div class="f1-buttons" >
                              <button type="button" id="btn-previous-step3" class="btn btn-previous">
                              Previous
                              </button>
                              <button 
                                 type="button"
                                 id="btn-pay-amount"
                                 class="btn btn-primary"
                                 >
                              Submit
                              </button>
                              <div class="spinner hidden" id="spinner"></div>
                           </div>
                        </div>
                     </fieldset>
                     <fieldset>
                        <h4>Payment Confirmation :</h4>
                        <div class="col-md-8 col-md-offset-2">
                           <div id="myalert" class="alert alert-success" role="alert">
                              Thank you very much you successfully purchased SP Currency.
                           </div>
                           <div class="form-group">
                              <input
                                 type="text"
                                 name="f1-full-name"
                                 class="f1-full-name form-control"
                                 id="f1-full-name"
                                 value=""
                                 disabled
                                 />
                           </div>
                           <div class="form-group">
                              <input
                                 type="text"
                                 name="f1-paid-amount"
                                 class="f1-paid-amount form-control"
                                 id="f1-paid-amount"
                                 value=""
                                 disabled
                                 />
                           </div>
                           <div class="f1-buttons">
                              <a href="<?= $HomeUrl; ?>" class="btn btn-primary">
                              Close
                              </a>
                           </div>
                        </div>
                     </fieldset>
                  </form>
                  
                  <form id="paypal-form" action="<?=$serverURL?>/paypal-payment.php" method="post">
                  <!-- <form id="paypal-form" action="https://www.paypal.com/cgi-bin/webscr" method="post"> -->

                     <!-- Identify your business so that you can collect the payments. -->
                     <input type="hidden" name="business"
                        value="adrianmunteanudev@gmail.com">
                        <!-- <input type="hidden" name="business"value="sb-vbaq4714515817@personal.example.com"> -->
                     <!-- Specify a Donate button. -->
                     <input type="hidden" name="cmd" value="_donations">
                     <!-- Specify details about the contribution -->
                     <input type="hidden" name="item_name" value="Shaiya Epic">
                     <input type="hidden" name="item_number" value="We accept all your contributions to help with our project. Thanks for helping us!">
                     <input type="hidden" id="amount" name="amount" value="10.00">
                     <input type="hidden" name="currency_code" value="EUR">
                     <input type="hidden" name="cancel_return" value="<?=$serverURL?>/"> 
                     <input type="hidden" name="user_id" value="<?= $UserUID ?>"> 
                     <input type="hidden" name="return" value="<?=$serverURL?>/success.php">  
                     <input type="hidden" name="notify_url" value="<?=$serverURL?>/notifypayment.php?user_id=<?= $UserUID ?>">  
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Javascript -->
      <script src="<?= $assetPath ?>assets/js/jquery-1.11.1.min.js"></script>
      <script src="<?= $assetPath ?>assets/bootstrap/js/bootstrap.min.js"></script>
      <script src="<?= $assetPath ?>assets/js/jquery.backstretch.min.js"></script>
      <script src="<?= $assetPath ?>assets/js/retina-1.1.0.min.js"></script>
      <!-- Stripe Setup-->
      <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
      <script src="https://js.stripe.com/v3/"></script>
      <script src="<?= $assetPath ?>assets/js/scripts.js"></script>
      <!--[if lt IE 10]>
      <script src="assets/js/placeholder.js"></script>
      <![endif]-->
   </body>
</html>