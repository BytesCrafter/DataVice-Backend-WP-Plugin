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

#### DELETE USER CONTACTS => /datavice/v1/contact/users/delete
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
        "message" : "Contact successfully deleted!"
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
        "message" : "Contact successfully deleted!"
    }
```


#### CONTACT LIST TYPE BY OWNER ID (USERS) => /datavice/v1/contact/users/list/type
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

#### CONTACT LIST TYPE BY OWNER ID (STORES) => /datavice/v1/contact/stores/list/type
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