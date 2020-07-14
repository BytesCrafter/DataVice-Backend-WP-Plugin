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