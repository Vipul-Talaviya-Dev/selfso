<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>API DOCS</title>
        <link href="{{ asset('documents/css/style.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('documents/js/jquery-latest.min.js') }}"></script>
        <script type='text/javascript' src="{{ asset('documents/js/menu.js') }}"></script>
    </head>
    <body>
        <div id='cssmenu'>
            <ul>
                <li><a href="#apiUrl"><span>API URL</span></a></li>
                <li><a href="#categories"><span>Categories</span></a></li>
                <li><a href="#login"><span>Login</span></a></li>
                <li><a href="#register"><span>Register</span></a></li>
                <li><a href="#forgot-password"><span>Forgot Password</span></a></li>
                <li><a href="#logout"><span>Logout</span></a></li>
                <li><a href="#profile"><span>User Profile</span></a></li>
                <li><a href="#profile-update"><span>Profile Update</span></a></li>
                <li><a href="#passwordReset"><span>Password Reset</span></a></li>
                <li><a href="#friend-request-list"><span>Friend Request List</span></a></li>
                <li><a href="#friend-request"><span>Friend Request For (Send, Cancel)</span></a></li>
                <li><a href="#friend-request-confirm"><span>Friend Request Confirm</span></a></li>
                <li><a href="#my-friends"><span>My Friends</span></a></li>
                <li><a href="#search-friends"><span>Search Friends</span></a></li>
                <li><a href="#post-create"><span>Post Create</span></a></li>
                <li><a href="#my-posts"><span>My Posts</span></a></li>
                <li><a href="#post-like"><span>Post Like & DisLike</span></a></li>
                <li><a href="#posts"><span>All User Posts</span></a></li>
                <li><a href="#post-comment"><span>Post Comment</span></a></li>
                <li><a href="#comments"><span>comments</span></a></li>
                <li><a href="#comment-like"><span>Comment Like</span></a></li>
                <li><a href="#post-delete"><span>Post Delete</span></a></li>
                <li><a href="#post-save"><span>Post Save</span></a></li>
                <li><a href="#save-post-list"><span>Save Post List</span></a></li>
                <li><a href="#post-share"><span>Post Share</span></a></li>
                <li><a href="#add-story"><span>Add Story</span></a></li>
                <li><a href="#stories"><span>All User Stories</span></a></li>
                <li><a href="#my-stories"><span>My Stories</span></a></li>
                <li><a href="#story-comment"><span>Story Comment</span></a></li>
            </ul>
        </div>
        <div class="panel right">
            <h2>API URL</h2>
            <div>
                <a name="apiUrl"><h3># API URL</h3></a>
                <div class="description">
                    Main URL of API.
                </div>
                <div class="call"> 
                    <span class="url">http://www.selfso.altsolution.in/api/v1/</span>
                </div>
            </div>

            <h2>Categories</h2>
            <div>
                <a name="categories"><h3># Categories</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">categories</span>
                </div>
                <div class="params">
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "categories": [
                                        {
                                            "id": 1,
                                            "name": "Business"
                                        },
                                        {
                                            "id": 2,
                                            "name": "Education"
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Login</h2>
            <div>
                <a name="login"><h3># Login</h3></a>
                <!-- <div class="description">
                    This method will return a specific Authorize Token.
                </div> -->
                
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">login</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ email:string }</strong> Email <b>(required)</b></li>
                        <li><strong>{ password:string }</strong> Password <b>(required)</b></li>
                        <li><strong>{ fcm_token:string }</strong> Fcm Token <b>(optional)</b></li>
                        
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Successfully Login.",
                                "data": {
                                    "token": "eyJpdiI6IkhESWJwazlZSHRvOVpnbTgvb3FqaEE9PSIsInZhbHVlIjoianUyY2VWTUg3ZjY1Z1p4bXM0dzBUREJRekw0bUpsMkxyMUlOQXZmNndUUUsyamFXTHdRcnBTOGZWTHFZekUvNFM4clJxNDBkdkd1UGtCaUVnVjk4anc9PSIsIm1hYyI6ImE5ZDVjNjI0NmYzYWRmOTYzYTg3YWFhOTFlYmE5ODM4OThiODhjZDdmNWY3ZWVjZmVlZGQwOGY0Y2IxYWI3YjEifQ==",
                                    "user": {
                                        "id": 1,
                                        "first_name": "Test",
                                        "last_name": "Test",
                                        "email": "test@gmail.com",
                                        "mobile": "99983XXXXX",
                                        "image": ""
                                    }
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Register</h2>
            <div>
                <a name="register"><h3># Register</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">register</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li>form-data</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ first_name:string }</strong> First Name <b>(required)</b></li>
                        <li><strong>{ last_name:string }</strong> Last Name <b>(required)</b></li>
                        <li><strong>{ email:string }</strong> Email <b>(required)</b></li>
                        <li><strong>{ password:string }</strong> Password <b>(required)</b></li>
                        <li><strong>{ mobile:Numeric }</strong> Mobile <b>(required)</b></li>
                        <li><strong>{ device:Numeric }</strong> Device(1: Android, 2: IOS) <b>(required)</b></li>
                        <li><strong>{ accountType:Numeric }</strong> Account Type(1: Public, 2: Private) <b>(required)</b></li>
                        <li><strong>{ loginType:Numeric }</strong> Login Type(1: App, 2: Google, 3: Facebook) <b>(required)</b></li>
                        <li><strong>{ image:file }</strong> Image <b>(optional)</b></li>
                        <li><strong>{ categories:json }</strong> Categories <b>(optional)</b></li>
                        <li><strong>{ fcm_token:string }</strong> Fcm Token <b>(optional)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 201,
                                "message": "Successfully Registered.",
                                "data": {
                                    "token": "eyJpdiI6InNUZzVqRjZFV3JuN2JhdVNWelVNWlE9PSIsInZhbHVlIjoieE9VZzV1NXFlVGk0b1I3K1hNaWxBM0JXSUtrTjFIY3ZibFRNMEhjNkFETElBdGdUZlFvbTViRk5GZUxLVnA0N3lsTGhqbWxqQUdOWjdKT0JzVDJlNUVZc1I1OEdOOExGQktQNVhZRVEzZGc9IiwibWFjIjoiNmEwM2IwZjlmYTNlNGFmZTM5MGNiYTYwZmU0NjZlMjc3MGRjMDUyMjdlMjU3ZjhlMjhhOTZlNWEwZGRlNmUzMSJ9",
                                    "user": {
                                        "id": 15,
                                        "first_name": "Test",
                                        "last_name": "Test",
                                        "email": "test@gmail.com",
                                        "mobile": "1234567890",
                                        "image": ""
                                    }
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Forgot Password</h2>
            <div>
                <a name="forgot-password"><h3># Forgot Password</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">forgot-password</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ email:string }</strong> Email <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Please Check Your Mail",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Logout</h2>
            <div>
                <a name="logout"><h3># Logout</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">logout</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Logout Successfully.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>User Profile</h2>
            <div>
                <a name="profile"><h3># User Profile</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">profile</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "user": {
                                        "id": 1,
                                        "first_name": "Test",
                                        "last_name": "Test",
                                        "email": "test@gmail.com",
                                        "mobile": "99983XXXXX",
                                        "image": ""
                                    }
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Profile Update</h2>
            <div>
                <a name="profile-update"><h3># Profile Update</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">profile-update</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li>form-data</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ first_name:string }</strong> First Name <b>(required)</b></li>
                        <li><strong>{ last_name:string }</strong> Last Name <b>(required)</b></li>
                        <li><strong>{ email:string }</strong> Email <b>(required)</b></li>
                        <li><strong>{ mobile:Numeric }</strong> Mobile <b>(required)</b></li>
                        <li><strong>{ image:file }</strong> Image <b>(optional)</b></li>
                        <li><strong>{ categories:json }</strong> Categories <b>(optional)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Successfully Profile Updated.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Password Reset</h2>
            <div>
                <a name="passwordReset"><h3># Password Reset</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">passwordReset</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ oldPassword:string }</strong> Old Password <b>(required)</b></li>
                        <li><strong>{ newPassword:string }</strong> New Password <b>(required)</b></li>
                        <li><strong>{ confirmPassword:string }</strong> Confirm Password <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Password has been changed successfully.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Friend Request List</h2>
            <div>
                <a name="friend-request-list"><h3># Friend Request List</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">friend-request-list</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "friends": [
                                        {
                                            "userConfirmId": 2,
                                            "first_name": "Test",
                                            "last_name": "Patel",
                                            "email": "testpatel@gmail.com",
                                            "mobile": "99983XXXXX",
                                            "image": ""
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Friend Request For (Send, Cancel)</h2>
            <div>
                <a name="friend-request"><h3># Friend Request For (Send, Cancel)</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">friend-request</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ userId:int }</strong> To User Id <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Friend request has been successfully sent.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Friend Request Confirm</h2>
            <div>
                <a name="friend-request-confirm"><h3># Friend Request Confirm</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">friend-request-confirm</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ userConfirmId:int }</strong> To User Id <b>(required)</b></li>
                        <li><strong>{ status:int }</strong> Status (1: Pending, 2: Accepted, 3: Blocked) <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Accept your friend request.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>My Friends</h2>
            <div>
                <a name="my-friends"><h3># My Friends</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">my-friends</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>

                    <p>Params</p>
                    <ul>
                        <li><strong>{ name:string }</strong> </li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "friends": [
                                        {
                                            "id": 2,
                                            "name": "Test Patel",
                                            "image": ""
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Search Friends</h2>
            <div>
                <a name="search-friends"><h3># Search Friends</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">search-friends</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ name:string }</strong> Name <b>(optional)</b></li>
                        <li><strong>{ categoryIds:json }</strong> Category Id <b>(optional)</b></li>
                        <li><strong>{ contactNos:json }</strong> Mobile No <b>(optional)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "users": [
                                        {
                                            "id": 2,
                                            "name": "Test Patel",
                                            "email": "testpatel@gmail.com",
                                            "image": "",
                                            "friendRequestButtonStatus": 2
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Post Create</h2>
            <div>
                <a name="post-create"><h3># Post Create</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">post-create</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>form-data</strong></li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ categoryId:int }</strong> Category Id <b>(required)</b></li>
                        <li><strong>{ media:File }</strong> Image Or Video <b>(optional)</b></li>
                        <li><strong>{ description:string }</strong> Description <b>(optional)</b></li>
                        <li><strong>{ link:string }</strong> Url <b>(optional)</b></li>
                        <li><strong>{ type:int }</strong> Type (1: Image, 2: Video) <b>(required)</b></li>
                        <li><strong>{ tagFriends:json }</strong> Tage User Ids <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 201,
                                "message": "Successfully add new post.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>My Posts</h2>
            <div>
                <a name="my-posts"><h3># My Posts</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">my-posts</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <p>Params</p>
                    <ul>
                        <li><strong>{ categoryId:int }</strong> Category Id</li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "feeds": [
                                        {
                                            "id": 9,
                                            "media": "",
                                            "description": "Hello",
                                            "link": "",
                                            "type": 1,
                                            "likeCount": 0,
                                            "commentCount": 0,
                                            "savePostFlag": 0,
                                            "postLikeFlag": 0,
                                            "createdAt": "1 minute ago",
                                            "user": {
                                                "id": 1,
                                                "first_name": "Test",
                                                "last_name": "Test",
                                                "email": "test@gmail.com",
                                                "mobile": "1234567890",
                                                "image": ""
                                            },
                                            "tagFriends": [
                                                {
                                                    "id": 2,
                                                    "name": "Test Patel",
                                                    "image": ""
                                                }
                                            ]
                                        }
                                    ],
                                    "discovers": [
                                        {
                                            "id": 9,
                                            "media": "",
                                            "description": "Hello",
                                            "link": "",
                                            "type": 1,
                                            "likeCount": 0,
                                            "commentCount": 0,
                                            "savePostFlag": 0,
                                            "postLikeFlag": 0,
                                            "createdAt": "1 minute ago",
                                            "user": {
                                                "id": 1,
                                                "first_name": "Test",
                                                "last_name": "Test",
                                                "email": "test@gmail.com",
                                                "mobile": "1234567890",
                                                "image": ""
                                            },
                                            "tagFriends": [
                                                {
                                                    "id": 2,
                                                    "name": "Test Patel",
                                                    "image": ""
                                                }
                                            ]
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Post Like & DisLike</h2>
            <div>
                <a name="post-like"><h3># Post Like & DisLike</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">post-like</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ postId:int }</strong> Post Id <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": ""
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>All User Posts</h2>
            <div>
                <a name="posts"><h3># All User Posts</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">posts</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <p>Params</p>
                    <ul>
                        <li><strong>{ categoryId:int }</strong> Category Id</li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "feeds": [
                                        {
                                            "id": 9,
                                            "media": "",
                                            "description": "Hello",
                                            "link": "",
                                            "type": 1,
                                            "likeCount": 0,
                                            "commentCount": 0,
                                            "savePostFlag": 0,
                                            "postLikeFlag": 0,
                                            "createdAt": "1 minute ago",
                                            "user": {
                                                "id": 1,
                                                "first_name": "Test",
                                                "last_name": "Test",
                                                "email": "test@gmail.com",
                                                "mobile": "1234567890",
                                                "image": ""
                                            },
                                            "tagFriends": [
                                                {
                                                    "id": 2,
                                                    "name": "Test Patel",
                                                    "image": ""
                                                }
                                            ]
                                        }
                                    ],
                                    "discovers": [
                                        {
                                            "id": 9,
                                            "media": "",
                                            "description": "Hello",
                                            "link": "",
                                            "type": 1,
                                            "likeCount": 0,
                                            "commentCount": 0,
                                            "savePostFlag": 0,
                                            "postLikeFlag": 0,
                                            "createdAt": "1 minute ago",
                                            "user": {
                                                "id": 1,
                                                "first_name": "Test",
                                                "last_name": "Test",
                                                "email": "test@gmail.com",
                                                "mobile": "1234567890",
                                                "image": ""
                                            },
                                            "tagFriends": [
                                                {
                                                    "id": 2,
                                                    "name": "Test Patel",
                                                    "image": ""
                                                }
                                            ]
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Post Comment</h2>
            <div>
                <a name="post-comment"><h3># Post Comment</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">post-comment</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ postId:int }</strong> Post Id <b>(required)</b></li>
                        <li><strong>{ commentId:int }</strong> Parent Comment Id <b>(optional)</b></li>
                        <li><strong>{ comment:string }</strong> Comment <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 201,
                                "message": "Successfully add new comment.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Comments</h2>
            <div>
                <a name="comments"><h3># Comments</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">comments</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ postId:int }</strong> Post Id <b>(required)</b></li>
                        <li><strong>{ commentId:int }</strong> Parent Comment Id <b>(optional)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "comments": [
                                        {
                                            "id": 1,
                                            "comment": "Nice Pic",
                                            "createdAt": "2 days ago",
                                            "likeCount": 1,
                                            "commentLikeFlag": 1,
                                            "user": {
                                                "id": 1,
                                                "first_name": "Test",
                                                "last_name": "Patel",
                                                "email": "testpatel@gmail.com",
                                                "mobile": "99983XXXXX",
                                                "image": ""
                                            }
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Comment Like & DisLike</h2>
            <div>
                <a name="comment-like"><h3># Comment Like & DisLike</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">comment-like</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ commentId:int }</strong> Comment Id <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": ""
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Post Delete</h2>
            <div>
                <a name="post-delete"><h3># Post Delete</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">post/delete/{id}</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": "Post has been successfully deleted",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Post Save</h2>
            <div>
                <a name="post-save"><h3># Post Save</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">post-save</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ postId:int }</strong> Post Id <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": ""
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Save Post List</h2>
            <div>
                <a name="save-post-list"><h3># Save Post List</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">save-post-list</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "posts": [
                                        {
                                            "id": 1,
                                            "media": "",
                                            "description": "Hello add Video",
                                            "link": "",
                                            "type": 1,
                                            "likeCount": 0,
                                            "commentCount": 2,
                                            "savePostFlag": 1,
                                            "createdAt": "2 days ago",
                                            "tagFriends": []
                                        }
                                    ]
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Post Share</h2>
            <div>
                <a name="post-share"><h3># Post Share</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">post-share</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ postId:int }</strong> Post Id <b>(required)</b></li>
                        <li><strong>{ userIds:int }</strong> User Ids <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "message": ""
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Add Story</h2>
            <div>
                <a name="add-story"><h3># Add Story</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">add-story</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>form-data</strong></li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ media:File }</strong> Image Or Video <b>(required)</b></li>
                        <li><strong>{ description:string }</strong> Description <b>(optional)</b></li>
                        <li><strong>{ addMemory:int }</strong> Add Memory (1: Yes, 0: No) <b>(optional)</b></li>
                        <li><strong>{ type:int }</strong> Type (1: Image, 2: Video) <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 201,
                                "message": "Successfully add story.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>All User Stories</h2>
            <div>
                <a name="stories"><h3># All User Stories</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">stories</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "myStories": {
                                        "id": 1,
                                        "first_name": "Test",
                                        "last_name": "Test",
                                        "email": "test@gmail.com",
                                        "mobile": "1234567890",
                                        "image": "",
                                        "stories": [
                                            {
                                                "id": 4,
                                                "media": "",
                                                "description": "Add Test Story",
                                                "createdAt": "2 hours ago",
                                                "addMemory": 1,
                                                "type": 0
                                            }
                                        ]
                                    },
                                    "otherStories": []
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>My Stories</h2>
            <div>
                <a name="my-stories"><h3># My Stories</h3></a>
                <div class="call"> 
                    <span class="method get">GET</span> 
                    <span class="url">my-stories</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 200,
                                "data": {
                                    "myStories": {
                                        "id": 1,
                                        "first_name": "Test",
                                        "last_name": "Test",
                                        "email": "test@gmail.com",
                                        "mobile": "1234567890",
                                        "image": "",
                                        "stories": [
                                            {
                                                "id": 4,
                                                "media": "",
                                                "description": "Add Test Story",
                                                "createdAt": "2 hours ago",
                                                "addMemory": 1,
                                                "type": 0
                                            }
                                        ]
                                    },
                                    "otherStories": []
                                }
                            }
                        </code>
                    </pre>
                </div>
            </div>

            <h2>Story Comment</h2>
            <div>
                <a name="story-comment"><h3># Story Comment</h3></a>
                <div class="call"> 
                    <span class="method post">POST</span> 
                    <span class="url">post-comment</span>
                </div>
                <div class="params">
                    <p>Headers</p>
                    <ul>
                        <li><strong>{ Authorization:string }</strong> API token <b>(required)</b></li>
                        <li><strong>Content-Type</strong> application/json</li>
                    </ul>
                    <p>Parameters</p>
                    <ul>
                        <li><strong>{ storyId:int }</strong> Story Id <b>(required)</b></li>
                        <li><strong>{ storyUserId:int }</strong> Story User Id <b>(required)</b></li>
                        <li><strong>{ message:string }</strong> Message <b>(required)</b></li>
                    </ul>
                    <hr />
                    
                    <p>Result</p>           
                    <pre>
                        <code>                  
                            {
                                "status" : (int),
                                "message": (string) http result message,
                                "data": (obj) { data }
                            }
                        </code>
                    </pre>
                    
                    <hr/>
                    <p>Data Example</p>
                    <pre>
                        <code>
                            {
                                "status": 201,
                                "message": "Successfully add story message.",
                                "data": []
                            }
                        </code>
                    </pre>
                </div>
            </div>

        </div>
    </body>
</html>
