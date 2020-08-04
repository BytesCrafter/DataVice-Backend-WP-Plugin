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

#### USERDATA REQUEST => /datavice/api/v1/user/data

REQUEST:
- Method: POST
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
        "uname": "demoguy",
        "dname": "demoguy",
        "email": "demoguy@bytescrafter.net",
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
                "prov_code": "1401",
                "city_code": "140101"
            },
            {
                "id": "1381",
                "citymun_name": "Boliney",
                "prov_code": "1401",
                "city_code": "140102"
            },
            {
                "id": "1382",
                "citymun_name": "Bucay",
                "prov_code": "1401",
                "city_code": "140103"
            },
            {
                "id": "1383",
                "citymun_name": "Bucloc",
                "prov_code": "1401",
                "city_code": "140104"
            },
            {
                "id": "1384",
                "citymun_name": "Daguioman",
                "prov_code": "1401",
                "city_code": "140105"
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
                "brgy_name": "Alabang",
                "prov_code": "1376",
                "city_code": "137603"
            },
            {
                "id": "36790",
                "brgy_name": "Bayanan",
                "prov_code": "1376",
                "city_code": "137603"
            },
            {
                "id": "36791",
                "brgy_name": "Buli",
                "prov_code": "1376",
                "city_code": "137603"
            },
            {
                "id": "36792",
                "brgy_name": "Cupang",
                "prov_code": "1376",
                "city_code": "137603"
            },
        ]
    }
```

#### INSERT USER CONTACTS => /datavice/v1/contact/users/insert
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=type (value=type of contact), key=value (value= value of contact), key=id (value = id of owner of this contact) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'Email not in valid format.' data: null
- code: 'failed' message: 'An error occured while submitting data to the server' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "message" : "Contact added successfully!"
    }
```

#### FETCH ACTIVE USER CONTACT BY TYPES => /datavice/v1/contact/users/list/type
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=type (value=type of contact), key=id (value = id of owner of this contact) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": {
            "list": [
                {
                    "ID": "10",
                    "types": "email",
                    "status": "1",
                    "email": "demoguy@bytescrafter.net"
                }
            ]
        }
    }
```


#### INSERT STORE CONTACTS => /datavice/v1/contact/stores/insert
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=type (value=type of contact), key=value (value= value of contact), key=id (value = id of owner of this contact) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'An error occured while submitting data to the server' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "message" : "Contact added successfully!"
    }
```


#### DELETE STORE CONTACTS => /datavice/v1/contact/stores/delete
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=ctc (value=contact id) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'An error occured while submitting data to the server' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "message" : "Contact set to inactive."
    }
```

#### SELECT ALL STORE CONTACTS => /datavice/v1/contact/stores/list/all
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=stid (value=store id) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'User not found!' data: null
- code: 'failed' message: 'An error occured while submitting data to the server' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "data": {
            "list": [
                {
                    "ID": "6",
                    "status": "1",
                    "types": "phone",
                    "child_val": "092921125341",
                    "stid": "1",
                    "created_by": "1",
                    "date_created": "2020-08-04 08:21:15"
                },
                {
                    "ID": "7",
                    "status": "1",
                    "types": "phone",
                    "child_val": "092921125341",
                    "stid": "1",
                    "created_by": "1",
                    "date_created": "2020-08-04 08:41:22"
                }
            ]
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