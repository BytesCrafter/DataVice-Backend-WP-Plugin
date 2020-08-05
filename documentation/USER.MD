# DataVice - WordPress Plugin

The DataVice for WordPress is a user management for your application or game users. This is an alternative to firebase which you can host your own.

### REST API Implementation

#### CREDENTIAL AUTHENTICATE => /datavice/api/v1/user/auth

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=UN (value=Username), key=PW (value=Password)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Authentication Unknown!' data: null
- code: 'failed' message: 'Unknown username. Check again or try your email address.' data: null
- code: 'failed' message: '<strong>ERROR</strong>: The password you entered for the username <strong>USERNAME HERE</strong> is incorrect. <a href=\"http://localhost/wp-login.php?action=lostpassword\">Lost your password?</a>' data: null

IF SUCCESS:
```
    {
        "status": "success",
        "data": {
            "snky": "daMjLapFixr6olYJbyYcWPYssHcV7oNdHkM6HfDdHaI",
            "wpid": 1
        }
    }
```

#### Verification => /datavice/api/v1/user/verify

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Authentication Unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Not Found!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Expired!' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "email": "demoguy@bytescrafter.net",
        "uname": "demoguy"
    }
```

#### FORGOTTEN OR LOST PASSWORD => /datavice/api/v1/user/forgot

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=UN (value=Username or value=Email)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Email or username doesn't exists!' data: null
- code: 'failed' message: 'Failed to send an email!' data: null

IF SUCCESS:
```
    {
        "status": "success",
        "message": "An email has been sent to your email address."
    }
```

#### CHANGE PASSWORD => /datavice/api/v1/user/reset

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=key (value=Key), key=PW (value=New Password)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Invalid key' data: null

IF SUCCESS:
```
    {
        "status": "success",
        "message": "Password updated successfully!"
    }
```

#### SIGN UP => /datavice/api/v1/user/signup
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=un (value=username), key=em (value=email), key=fn (value=first_name), key=ln (value=last_name), key=gd (value=gender), key=pr (value=province id), key=ct (value=city id) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Verification Unknown!' data: null
- code: 'failed' message: 'Username Already Exists' data: null
- code: 'unknown' message: 'Please contact your administrator. User Creation Unknown!' data: null
- code: 'unknown' message: 'Please contact your administrator. User Authentication key Creation Unknown!' data: null
- code: 'unknown' message: 'Please contact your administrator. User Creation Unknown!' data: null

IF SUCCESS:
```
    {
       "status" => "Success",
       "message" => "Please check your email account for activation.",
    }
```