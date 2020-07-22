# DataVice - WordPress Plugin

The DataVice for WordPress is a user management for your application or game users. This is an alternative to firebase which you can host your own.

### REST API Implementation

#### CREDENTIAL AUTHENTICATE

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=UN (value=Username), key=PW (value=Password)

RESPONSE:
- code: 'unknown_request' message: 'Please contact your administrator.' data: null
- code: 'invalid_username' message: 'Unknown username. Check again or try your email address.' data: null
- code: 'incorrect_password' message: '<strong>ERROR</strong>: The password you entered for the username <strong>USERNAME HERE</strong> is incorrect. <a href=\"http://localhost/wp-login.php?action=lostpassword\">Lost your password?</a>' data: null

IF SUCCESS:
```
    {
        "data": {
            "ID": "4",
            "user_login": "Demoguy",
            "user_pass": "$P$BF6bLMMQC.lm5wRxTZqeDv/Q6vWGgO/",
            "user_nicename": "demoguy",
            "user_email": "demoguy@bytescrafter.net",
            "user_url": "",
            "user_registered": "2020-03-14 05:39:09",
            "user_activation_key": "1584164356:$P$BGXi.agvSOc.KVBKdih.w/BGZtRQb00",
            "user_status": "0",
            "display_name": "Demo Guy"
        },
        "ID": 4,
        "caps": {
            "subscriber": true
        },
        "cap_key": "wp_capabilities",
        "roles": [
            "subscriber"
        ],
        "allcaps": {
            "read": true,
            "level_0": true,
            "subscriber": true
        },
        "filter": null
    }
```

#### USER DATA REQUEST

REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=ID (value=User ID)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Verification Unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. Invalid ID' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null

IF SUCCESS:
```
    {
        "status": "success",
        "data": {
            "uname": "Demoguy",
            "dname": "demoguy",
            "email": "demoguy@bytescrafter.net",
            "roles": [
                "administrator"
            ],
            "fname": "Demo",
            "lname": "Guy"
    }
}
```

#### FORGOTTEN OR LOST PASSWORD

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=UN (value=Username or value=Email)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Authentication Unknown!' data: null
- code: 'failed' message: 'Email or username doesn't exists!' data: null
- code: 'failed' message: 'Failed to send an email!' data: null

IF SUCCESS:
```
    {
        "status": "success",
        "message": "An email has been sent to your email address."
    }
```

#### CREATING NEW PASSWORD

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=key (value=Key), key=PW (value=New Password)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Authentication Unknown!' data: null
- code: 'failed' message: 'Invalid key' data: null
- code: 'failed' message: 'Failed to send an email!' data: null

IF SUCCESS:
```
    {
        "status": "success",
        "message": "Password updated successfully!"
    }
```

#### SIGN UP
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=UN (value=username), key=email (value=email), key=FN (value=first_name), key=LN (value=last_name), key=gender (value=gender), key=province (value=province), key=city (value=city) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Verification Unknown!' data: null
- code: 'failed' message: 'Username Already Exists' data: null
- code: 'unknown' message: 'Please contact your administrator. User Creation Unknown!' data: null
- code: 'unknown' message: 'Please contact your administrator. User Authentication key Creation Unknown!' data: null
- code: 'unknown' message: 'Please contact your administrator. User Creation Unknown!' data: null
- code: 'Success' message: 'Please check your email account for activation.' data: null

IF SUCCESS:
```
    {
       "status" => "Success",
       "message" => "Please check your email account for activation.",
    }
```

#### NEWS FEED
REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=ID (value=User ID)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'User not found!' data: null

IF SUCCESS:
```
    {
       {
        "status": "success",
        "data": {
                "list": [
                    {
                        "id": "15"
                    },
                    {
                        "id": "14"
                    },
                    {
                        "id": "13"
                    },
                    {
                        "id": "12"
                    },
                    {
                        "id": "11"
                    },
                    {
                        "id": "10"
                    },
                    {
                        "id": "9"
                    },
                    {
                        "id": "8"
                    },
                    {
                        "id": "7"
                    },
                    {
                        "id": "6"
                    },
                    {
                        "id": "5"
                    },
                    {
                        "id": "4"
                    }
                ],
                "last_id": {
                    "id": "4"
                }
            }
        }
    }
```

#### ADDITIONAL NEWS FEED
REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=ID (value=User ID) key=LID (value=Last ID of post)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request unknown!' data: null
- code: 'failed' message: 'Parameters not in valid format!' data: null
- code: 'failed' message: 'User not found!' data: null
- code: 'failed' message: 'No more posts to see!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": {
            "list": [
                {
                    "id": "5"
                },
                {
                    "id": "4"
                },
                {
                    "id": "3"
                },
                {
                    "id": "2"
                },
                {
                    "id": "1"
                }
            ],
            "last_id": {
                "id": "1"
            }
        }
    }
```



## Contributing

Please read [CONTRIBUTING](CONTRIBUTING) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/BytesCrafter/DataVice-WP-Plugin/tags). 

## Authors

* **Bytes Crafter** - *Initial work* - [Github](https://github.com/BytesCrafter/DataVice-WP-Plugin.git)

See also the list of [contributors](https://github.com/BytesCrafter/DataVice-WP-Plugin/graphs/contributors) who participated in this project.

## License

This project is licensed under the GNU GPL License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* WordPress.Org
* MariaDB Developer