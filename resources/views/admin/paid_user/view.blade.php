                     <?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
@extends('admin.layout.admin')
@section('content')
    <!-- ============================================================== -->
    <link href="{{ URL::asset('public/admin/plugins/datatables/buttons.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/fixedHeader.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/scroller.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/dataTables.colVis.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/admin/plugins/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
  
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
             <div class="container">
                 <!-- end row -->
                 <br>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="card-box">
                                    <h4 class="header-title m-t-0 m-b-30">User Details</h4>
      
                                    <div class="pull-right"><a href="{{ URL::to('admin/wallet-users') }}" title="View All Users" class="btn btn-danger">View All Users</a></div>
                                    
                                    
                                    <ul class="nav nav-tabs tabs-bordered">
                                        <li class="active">
                                            <a href="#home-b1" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                                <span class="hidden-xs">Wallet Details</span>
                                            </a>
                                        </li>
                                        <li >
                                            <a href="#profile-b1" data-toggle="tab" aria-expanded="true">
                                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                                <span class="hidden-xs">Personal Details</span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#messages-b1" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
                                                <span class="hidden-xs">Identity Proof</span>
                                            </a>
                                        </li>
                                        
                                         <li class="">
                                            <a href="#bank-b1" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                                <span class="hidden-xs">Bank Details</span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#settings-b1" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                                <span class="hidden-xs">Login History</span>
                                            </a>
                                        </li>
                                        
                                         
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home-b1">
                                         
                                         
                                         
                                          <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>BTC Transaction Id</b></div> <div class='col-md-8'>{{$user->btc_transaction_id}}</div>
                                            </div>
                                         
                                         
                                          <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>STC Address</b></div> <div class='col-md-8'>{{$user->user->wallet_address}}</div>
                                            </div>
                                          
                                          <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b> BTC Address</b></div> <div class='col-md-8'></div>
                                            </div>
                                          
                                         
                                          
                                         </div>
                                        <div class="tab-pane" id="profile-b1">
                                        
                                
                                            <div class="row">
                                                <div class="col-md-2"><b>Username</b></div> <div class='col-md-8'>{{$user->user->username}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Email</b></div> <div class='col-md-8'>{{$user->user->email}}</div>
                                            </div>
                                            
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Account Address</b></div> <div class='col-md-8'>{{$user->account_address}}</div>
                                            </div>
                                             <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Mobile</b></div> <div class='col-md-8'>{{$user->mobile_number}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Pincode</b></div> <div class='col-md-8'>{{$user->pincode}}</div>
                                            </div>
                                            <br><br>
                                             <div class="row">
                                                <div class="col-md-2"><b>City</b></div> <div class='col-md-8'>{{$user->city_id}}</div>
                                            </div>
                                            <br><br>
                                             <div class="row">
                                                <div class="col-md-2"><b>State</b></div> <div class='col-md-8'>{{$user->state_id}}</div>
                                            </div>
                                            
                                            <br><br>
                                             <div class="row">
                                                <div class="col-md-2"><b>Country</b></div> <div class='col-md-8'>{{$user->country_id}}</div>
                                            </div>
                                            
                                        </div>
                                        <div class="tab-pane" id="messages-b1">
                                              <div class="row">
                                                <div class="col-md-2"><b>Pancard No</b></div> <div class='col-md-8'>{{$user->pancard}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Pancard</b></div> <div class='col-md-8'>
                                                    <img src="{{env('MEMBER_IMAGE_URL')}}/account/{{$user->pancard_image}}" height='100' width='100'></div>
                                            </div>
                                            <br><br>
                                            
                                            <div class="row">    	 
                                                <div class="col-md-2"><b>Photo</b></div> <div class='col-md-8'> <img src="{{env('MEMBER_IMAGE_URL')}}/account/{{$user->photo}}" height='100' width='100'></div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Address Proof</b></div> <div class='col-md-8'> <img src="{{env('MEMBER_IMAGE_URL')}}/account/{{$user->address_proof}}" height='100' width='100'></div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>ID</b></div> <div class='col-md-8'> <img src="{{env('MEMBER_IMAGE_URL')}}/account/{{$user->id_photo}}" height='100' width='100'></div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="tab-pane" id="bank-b1">
                                             
                                             <div class="row">
                                                <div class="col-md-2"><b>Account Name </b></div> <div class='col-md-8'>{{$user->account_name}}</div>
                                            </div>
                                             <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Account No </b></div> <div class='col-md-8'>{{$user->account_no}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>IFSC </b></div> <div class='col-md-8'>{{$user->ifsc}}</div>
                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-2"><b>Account Address</b></div> <div class='col-md-8'>{{$user->account_address}}</div>
                                            </div>
                                            
                                            
                                         </div>
                                         
                                        <div class="tab-pane" id="settings-b1">
                                             <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">

                                    <h4 class="m-t-0 header-title"><b>Responsive example</b></h4>
                                    <p class="text-muted font-13 m-b-30">
                                        Responsive is an extension for DataTables that resolves that problem by optimising the
                                        table's layout for different screen sizes through the dynamic insertion and removal of
                                        columns from the table.
                                    </p>

                                    <table id="datatable-responsive"
                                           class="table table-striped  table-colored table-info dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                            <th>Extn.</th>
                                            <th>E-mail</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Tiger</td>
                                            <td>Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                            <td>5421</td>
                                            <td>t.nixon@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett</td>
                                            <td>Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td>$170,750</td>
                                            <td>8422</td>
                                            <td>g.winters@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton</td>
                                            <td>Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td>$86,000</td>
                                            <td>1562</td>
                                            <td>a.cox@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric</td>
                                            <td>Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td>$433,060</td>
                                            <td>6224</td>
                                            <td>c.kelly@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Airi</td>
                                            <td>Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td>$162,700</td>
                                            <td>5407</td>
                                            <td>a.satou@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle</td>
                                            <td>Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td>$372,000</td>
                                            <td>4804</td>
                                            <td>b.williamson@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Herrod</td>
                                            <td>Chandler</td>
                                            <td>Sales Assistant</td>
                                            <td>San Francisco</td>
                                            <td>59</td>
                                            <td>2012/08/06</td>
                                            <td>$137,500</td>
                                            <td>9608</td>
                                            <td>h.chandler@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Rhona</td>
                                            <td>Davidson</td>
                                            <td>Integration Specialist</td>
                                            <td>Tokyo</td>
                                            <td>55</td>
                                            <td>2010/10/14</td>
                                            <td>$327,900</td>
                                            <td>6200</td>
                                            <td>r.davidson@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Colleen</td>
                                            <td>Hurst</td>
                                            <td>Javascript Developer</td>
                                            <td>San Francisco</td>
                                            <td>39</td>
                                            <td>2009/09/15</td>
                                            <td>$205,500</td>
                                            <td>2360</td>
                                            <td>c.hurst@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Sonya</td>
                                            <td>Frost</td>
                                            <td>Software Engineer</td>
                                            <td>Edinburgh</td>
                                            <td>23</td>
                                            <td>2008/12/13</td>
                                            <td>$103,600</td>
                                            <td>1667</td>
                                            <td>s.frost@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Jena</td>
                                            <td>Gaines</td>
                                            <td>Office Manager</td>
                                            <td>London</td>
                                            <td>30</td>
                                            <td>2008/12/19</td>
                                            <td>$90,560</td>
                                            <td>3814</td>
                                            <td>j.gaines@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Quinn</td>
                                            <td>Flynn</td>
                                            <td>Support Lead</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2013/03/03</td>
                                            <td>$342,000</td>
                                            <td>9497</td>
                                            <td>q.flynn@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Charde</td>
                                            <td>Marshall</td>
                                            <td>Regional Director</td>
                                            <td>San Francisco</td>
                                            <td>36</td>
                                            <td>2008/10/16</td>
                                            <td>$470,600</td>
                                            <td>6741</td>
                                            <td>c.marshall@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Haley</td>
                                            <td>Kennedy</td>
                                            <td>Senior Marketing Designer</td>
                                            <td>London</td>
                                            <td>43</td>
                                            <td>2012/12/18</td>
                                            <td>$313,500</td>
                                            <td>3597</td>
                                            <td>h.kennedy@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Michelle</td>
                                            <td>House</td>
                                            <td>Integration Specialist</td>
                                            <td>Sidney</td>
                                            <td>37</td>
                                            <td>2011/06/02</td>
                                            <td>$95,400</td>
                                            <td>2769</td>
                                            <td>m.house@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Suki</td>
                                            <td>Burks</td>
                                            <td>Developer</td>
                                            <td>London</td>
                                            <td>53</td>
                                            <td>2009/10/22</td>
                                            <td>$114,500</td>
                                            <td>6832</td>
                                            <td>s.burks@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Prescott</td>
                                            <td>Bartlett</td>
                                            <td>Technical Author</td>
                                            <td>London</td>
                                            <td>27</td>
                                            <td>2011/05/07</td>
                                            <td>$145,000</td>
                                            <td>3606</td>
                                            <td>p.bartlett@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Gavin</td>
                                            <td>Cortez</td>
                                            <td>Team Leader</td>
                                            <td>San Francisco</td>
                                            <td>22</td>
                                            <td>2008/10/26</td>
                                            <td>$235,500</td>
                                            <td>2860</td>
                                            <td>g.cortez@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Martena</td>
                                            <td>Mccray</td>
                                            <td>Post-Sales support</td>
                                            <td>Edinburgh</td>
                                            <td>46</td>
                                            <td>2011/03/09</td>
                                            <td>$324,050</td>
                                            <td>8240</td>
                                            <td>m.mccray@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Unity</td>
                                            <td>Butler</td>
                                            <td>Marketing Designer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009/12/09</td>
                                            <td>$85,675</td>
                                            <td>5384</td>
                                            <td>u.butler@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Howard</td>
                                            <td>Hatfield</td>
                                            <td>Office Manager</td>
                                            <td>San Francisco</td>
                                            <td>51</td>
                                            <td>2008/12/16</td>
                                            <td>$164,500</td>
                                            <td>7031</td>
                                            <td>h.hatfield@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Hope</td>
                                            <td>Fuentes</td>
                                            <td>Secretary</td>
                                            <td>San Francisco</td>
                                            <td>41</td>
                                            <td>2010/02/12</td>
                                            <td>$109,850</td>
                                            <td>6318</td>
                                            <td>h.fuentes@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Vivian</td>
                                            <td>Harrell</td>
                                            <td>Financial Controller</td>
                                            <td>San Francisco</td>
                                            <td>62</td>
                                            <td>2009/02/14</td>
                                            <td>$452,500</td>
                                            <td>9422</td>
                                            <td>v.harrell@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Timothy</td>
                                            <td>Mooney</td>
                                            <td>Office Manager</td>
                                            <td>London</td>
                                            <td>37</td>
                                            <td>2008/12/11</td>
                                            <td>$136,200</td>
                                            <td>7580</td>
                                            <td>t.mooney@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Jackson</td>
                                            <td>Bradshaw</td>
                                            <td>Director</td>
                                            <td>New York</td>
                                            <td>65</td>
                                            <td>2008/09/26</td>
                                            <td>$645,750</td>
                                            <td>1042</td>
                                            <td>j.bradshaw@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Olivia</td>
                                            <td>Liang</td>
                                            <td>Support Engineer</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2011/02/03</td>
                                            <td>$234,500</td>
                                            <td>2120</td>
                                            <td>o.liang@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Bruno</td>
                                            <td>Nash</td>
                                            <td>Software Engineer</td>
                                            <td>London</td>
                                            <td>38</td>
                                            <td>2011/05/03</td>
                                            <td>$163,500</td>
                                            <td>6222</td>
                                            <td>b.nash@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Sakura</td>
                                            <td>Yamamoto</td>
                                            <td>Support Engineer</td>
                                            <td>Tokyo</td>
                                            <td>37</td>
                                            <td>2009/08/19</td>
                                            <td>$139,575</td>
                                            <td>9383</td>
                                            <td>s.yamamoto@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Thor</td>
                                            <td>Walton</td>
                                            <td>Developer</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2013/08/11</td>
                                            <td>$98,540</td>
                                            <td>8327</td>
                                            <td>t.walton@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Finn</td>
                                            <td>Camacho</td>
                                            <td>Support Engineer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009/07/07</td>
                                            <td>$87,500</td>
                                            <td>2927</td>
                                            <td>f.camacho@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Serge</td>
                                            <td>Baldwin</td>
                                            <td>Data Coordinator</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2012/04/09</td>
                                            <td>$138,575</td>
                                            <td>8352</td>
                                            <td>s.baldwin@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Zenaida</td>
                                            <td>Frank</td>
                                            <td>Software Engineer</td>
                                            <td>New York</td>
                                            <td>63</td>
                                            <td>2010/01/04</td>
                                            <td>$125,250</td>
                                            <td>7439</td>
                                            <td>z.frank@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Zorita</td>
                                            <td>Serrano</td>
                                            <td>Software Engineer</td>
                                            <td>San Francisco</td>
                                            <td>56</td>
                                            <td>2012/06/01</td>
                                            <td>$115,000</td>
                                            <td>4389</td>
                                            <td>z.serrano@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Jennifer</td>
                                            <td>Acosta</td>
                                            <td>Junior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>43</td>
                                            <td>2013/02/01</td>
                                            <td>$75,650</td>
                                            <td>3431</td>
                                            <td>j.acosta@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Cara</td>
                                            <td>Stevens</td>
                                            <td>Sales Assistant</td>
                                            <td>New York</td>
                                            <td>46</td>
                                            <td>2011/12/06</td>
                                            <td>$145,600</td>
                                            <td>3990</td>
                                            <td>c.stevens@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Hermione</td>
                                            <td>Butler</td>
                                            <td>Regional Director</td>
                                            <td>London</td>
                                            <td>47</td>
                                            <td>2011/03/21</td>
                                            <td>$356,250</td>
                                            <td>1016</td>
                                            <td>h.butler@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Lael</td>
                                            <td>Greer</td>
                                            <td>Systems Administrator</td>
                                            <td>London</td>
                                            <td>21</td>
                                            <td>2009/02/27</td>
                                            <td>$103,500</td>
                                            <td>6733</td>
                                            <td>l.greer@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Jonas</td>
                                            <td>Alexander</td>
                                            <td>Developer</td>
                                            <td>San Francisco</td>
                                            <td>30</td>
                                            <td>2010/07/14</td>
                                            <td>$86,500</td>
                                            <td>8196</td>
                                            <td>j.alexander@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Shad</td>
                                            <td>Decker</td>
                                            <td>Regional Director</td>
                                            <td>Edinburgh</td>
                                            <td>51</td>
                                            <td>2008/11/13</td>
                                            <td>$183,000</td>
                                            <td>6373</td>
                                            <td>s.decker@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Michael</td>
                                            <td>Bruce</td>
                                            <td>Javascript Developer</td>
                                            <td>Singapore</td>
                                            <td>29</td>
                                            <td>2011/06/27</td>
                                            <td>$183,000</td>
                                            <td>5384</td>
                                            <td>m.bruce@datatables.net</td>
                                        </tr>
                                        <tr>
                                            <td>Donna</td>
                                            <td>Snider</td>
                                            <td>Customer Support</td>
                                            <td>New York</td>
                                            <td>27</td>
                                            <td>2011/01/25</td>
                                            <td>$112,000</td>
                                            <td>4226</td>
                                            <td>d.snider@datatables.net</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                                         </div>
                                         
                                         
                                         
                                         
                                         
                                         
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            
             </div> <!-- container -->
        </div> <!-- container -->
        </div> <!-- content -->
    </div>
    
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    
      <script src="{{ URL::asset('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ URL::asset('public/admin/plugins/datatables/dataTables.bootstrap.js') }}"></script>
      <script src="{{ URL::asset('public/admin/plugins/datatables/dataTables.fixedHeader.min.js') }}"></script>
      <script src="{{ URL::asset('public/admin/plugins/datatables/dataTables.colVis.js') }}"></script>
      <script src="{{ URL::asset('public/admin/plugins/dataTables.responsive.min.js') }}"></script>
    
    @stop
