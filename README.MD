# DataVice - WordPress Plugin

The DataVice for WordPress is a user management for your application or game users. This is an alternative to firebase which you can host your own.

### REST API Implementation

[USER RESTAPI DOCS](https://github.com/BytesCrafter/DataVice-WP-Plugin/documentation/USER)

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
                "name": "Alabang",
                "code": "36789"
            },
            {
                "name": "Bayanan",
                "code": "36790"
            },
            {
                "name": "Buli",
                "code": "36791"
            },
            {
                "name": "Cupang",
                "code": "36792"
            },
        ]
    }
```


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


#### UPDATE FIRST NAME => /datavice/api/v1/settings/update_fname
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=fn (value=First Name) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'User not found!' data: null
- code: 'failed' message: 'This name is already exist' data: null
- code: 'unknown' message: 'Please contact your administrator. Update of First name Failed' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "message": "Data has been updated successfully"
    }
```


#### UPDATE LAST NAME => /datavice/api/v1/settings/update_lname
REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=fn (value=First Name) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'User not found!' data: null
- code: 'failed' message: 'This name is already exist' data: null
- code: 'unknown' message: 'Please contact your administrator. Update of First name Failed' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "message": "Data has been updated successfully"
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