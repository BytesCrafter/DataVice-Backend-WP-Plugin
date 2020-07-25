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
        "email": "lorenzbecislao@gmail.com",
        "uname": "admin"
    }
```

#### USERDATA REQUEST => /datavice/api/v1/user/data

REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=ID (value=User ID)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Authentication Unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Not Found!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Expired!' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null

IF SUCCESS:
```
{
    "status": "success",
    "data": {
        "uname": "admin",
        "dname": "admin",
        "email": "admin@bytescrafter.net",
        "ro": [
            "administrator"
        ],
        "fn": "",
        "ln": "",
        "av": "bytescrafter.net/assets/imagees/admin-avatar"
    }
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

#### PROFILE FEED LIST => /datavice/api/v1/feeds/profile
REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key)

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

#### ADDITIONAL PROFILE FEED LIST => /datavice/api/v1/feeds/p_feeds
REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=LID (value=Last ID of feed list)

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

#### HOME FEED LIST => /datavice/api/v1/feeds/home
REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key)

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

#### ADDITIONAL HOME FEED LIST => /datavice/api/v1/feeds/home_add_feeds
REQUEST:
- Method: GET
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=LID (value=Last ID of feed list)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'User not found!' data: null

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

#### GETTING COUNTRY LIST => /datavice/api/v1/user/ctry
REQUEST:
- Method: GET, POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key)

RESPONSE:
- code: 'failed' message: 'Please contact your administrator. Token Not Found!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Expired!' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null
- code: 'error' message: 'An error occured while fetching data from the server!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": [
            {
                "id": "1",
                "country_code": "AF",
                "country_name": "Afghanistan"
            },
            {
                "id": "2",
                "country_code": "AL",
                "country_name": "Albania"
            },
            {
                "id": "3",
                "country_code": "DZ",
                "country_name": "Algeria"
            },
            {
                "id": "4",
                "country_code": "DS",
                "country_name": "American Samoa"
            },
            {
                "id": "5",
                "country_code": "AD",
                "country_name": "Andorra"
            }
        ]
    }
```

#### GETTING PROVINCES => /datavice/api/v1/user/prv
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key)

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Not Found!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Expired!' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null
- code: 'error' message: 'An error occured while fetching data from the server!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": [
            {
                "id": "73",
                "prov": "Abra"
            },
            {
                 "id": "84",
                "prov": "Agusan Del Norte"
            },
            {
                "id": "85",
             "prov": "Agusan Del Sur"
            },
            {
                "id": "33",
                "prov": "Aklan"
            },
            {
                "id": "27",
                "prov": "Albay"
            }
        ]
    }
```

#### GETTING CITIES => /datavice/api/v1/user/city
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=PC (value=Province Code) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Not Found!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Expired!' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null
- code: 'unknown' message: 'Please contact your administrator. Invalid province code!' data: null
- code: 'error' message: 'An error occured while fetching data from the server!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": [
            {
                "id": "1380",
                "citymun_name": "Bangued (Capital)",
                "provCode": "1401",
                "citymunCode": "140101"
            },
            {
                "id": "1381",
                "citymun_name": "Boliney",
                "provCode": "1401",
                "citymunCode": "140102"
            },
            {
                "id": "1382",
                "citymun_name": "Bucay",
                "provCode": "1401",
                "citymunCode": "140103"
            },
            {
                "id": "1383",
                "citymun_name": "Bucloc",
                "provCode": "1401",
                "citymunCode": "140104"
            },
            {
                "id": "1384",
                "citymun_name": "Daguioman",
                "provCode": "1401",
                "citymunCode": "140105"
            }
        ]
    }
```

#### GETTING BARANGAYS => /datavice/api/v1/user/brgy
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=CC (value=City Code) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Not Found!' data: null
- code: 'failed' message: 'Please contact your administrator. Token Expired!' data: null
- code: 'failed' message: 'Please contact your administrator. User Not Found!' data: null
- code: 'unknown' message: 'Please contact your administrator. Invalid city code!' data: null
- code: 'error' message: 'An error occured while fetching data from the server!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": [
           {
                "id": "36789",
                "brgyCode": "137603001",
                "brgyDesc": "Alabang",
                "regCode": "13",
                "provCode": "1376",
                "citymunCode": "137603"
            },
            {
                "id": "36790",
                "brgyCode": "137603002",
                "brgyDesc": "Bayanan",
                "regCode": "13",
                "provCode": "1376",
                "citymunCode": "137603"
            },
            {
                "id": "36791",
                "brgyCode": "137603003",
                "brgyDesc": "Buli",
                "regCode": "13",
                "provCode": "1376",
                "citymunCode": "137603"
            },
            {
                "id": "36792",
                "brgyCode": "137603004",
                "brgyDesc": "Cupang",
                "regCode": "13",
                "provCode": "1376",
                "citymunCode": "137603"
            },
        ]
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